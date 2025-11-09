<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'variation_id',
        'product_name',
        'variation_details',
        'quantity',
        'unit_price',
        'subtotal',
        'vendor_earning',
        'admin_commission',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'vendor_earning' => 'decimal:2',
            'admin_commission' => 'decimal:2',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    // ==================== HELPER METHODS ====================

    public function fullProductName(): string
    {
        $name = $this->product_name;

        if ($this->variation_details) {
            $name .= ' - ' . $this->variation_details;
        }

        return $name;
    }
}
