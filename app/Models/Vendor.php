<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'phone',
        'address',
        'commission_rate',
        'status',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'commission_rate' => 'decimal:2',
        ];
    }

    // ==================== BOOT METHOD ====================

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug on creation
        static::creating(function ($vendor) {
            if (empty($vendor->shop_slug)) {
                $vendor->shop_slug = Str::slug($vendor->shop_name);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Get all orders through order items
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            OrderItem::class,
            'vendor_id',  // Foreign key on order_items
            'id',         // Foreign key on orders
            'id',         // Local key on vendors
            'order_id'    // Local key on order_items
        );
    }

    // ==================== SCOPES (Modern Laravel Feature) ====================

    // Scope: Get only approved vendors
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope: Get pending vendors
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: Get active vendors (approved + has products)
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->whereHas('products');
    }

    // ==================== ACCESSORS (Modern Syntax) ====================

    // Get full shop URL
    public function shopUrl(): string
    {
        return route('shop.show', $this->shop_slug);
    }

    // Get logo URL or default
    public function logoUrl(): string
    {
        return $this->shop_logo
            ? asset('storage/' . $this->shop_logo)
            : asset('images/default-shop.png');
    }

    // ==================== HELPER METHODS ====================

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Calculate total earnings
    public function totalEarnings(): float
    {
        return $this->orderItems()
            ->whereHas('order', fn($q) => $q->where('payment_status', 'completed'))
            ->sum('vendor_earning');
    }
}
