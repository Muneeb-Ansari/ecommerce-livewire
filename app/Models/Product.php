<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'discount_percentage',
        'sku',
        'status',
        'is_featured',
        'stock_quantity',
        'views_count',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'is_featured' => 'boolean',
            'stock_quantity' => 'integer',
            'views_count' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'active']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    // Search scope
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%");
        });
    }

    // Filter by price range
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    // ==================== ACCESSORS ====================

    // Calculate final price after discount
    public function finalPrice(): float
    {
        if ($this->discount_percentage > 0) {
            return $this->base_price - ($this->base_price * $this->discount_percentage / 100);
        }

        return $this->base_price;
    }

    // Get primary image
    public function primaryImage(): ?string
    {
        $image = $this->images()->where('is_primary', true)->first();

        return $image
            ? asset('storage/' . $image->image_path)
            : asset('images/placeholder-product.png');
    }

    // Average rating
    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // Total reviews count
    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    // ==================== HELPER METHODS ====================

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    public function isFeatured(): bool
    {
        return $this->is_featured;
    }

    public function hasDiscount(): bool
    {
        return $this->discount_percentage > 0;
    }

    // Increment views
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Check if user has wishlisted this product
    public function isWishlistedBy(?int $userId): bool
    {
        if (!$userId) return false;

        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    // Decrease stock
    public function decreaseStock(int $quantity): void
    {
        $this->decrement('stock_quantity', $quantity);
    }

    // Increase stock
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
    }
}
