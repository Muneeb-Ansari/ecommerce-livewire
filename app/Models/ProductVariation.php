<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_type',
        'variation_value',
        'price_adjustment',
        'stock_quantity',
        'sku',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    // Calculate final price (base + adjustment)
    public function finalPrice(): float
    {
        return $this->product->base_price + $this->price_adjustment;
    }

    // Get display name (e.g., "Size: XL")
    public function displayName(): string
    {
        return ucfirst($this->variation_type) . ': ' . $this->variation_value;
    }

    // ==================== SCOPES ====================

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('variation_type', $type);
    }

    // ==================== HELPER METHODS ====================

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}
