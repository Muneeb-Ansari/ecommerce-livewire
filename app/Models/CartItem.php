<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'quantity',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    // ==================== ACCESSORS ====================

    // Calculate subtotal for this item
    public function subtotal(): float
    {
        return $this->price * $this->quantity;
    }

    // Get product name with variation
    public function fullName(): string
    {
        $name = $this->product->name;

        if ($this->variation) {
            $name .= ' - ' . $this->variation->displayName();
        }

        return $name;
    }
}
