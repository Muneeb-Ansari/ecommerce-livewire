<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // ==================== ACCESSORS ====================

    // Calculate total items count
    public function totalItems(): int
    {
        return $this->items()->sum('quantity');
    }

    // Calculate subtotal
    public function subtotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    // Calculate total (with tax, shipping, etc.)
    public function total(): float
    {
        // Add tax, shipping logic here later
        return $this->subtotal();
    }

    // ==================== HELPER METHODS ====================

    public function isEmpty(): bool
    {
        return $this->items()->count() === 0;
    }

    public function clear(): void
    {
        $this->items()->delete();
    }
}
