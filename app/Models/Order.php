<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'user_id', 'customer_name', 'email', 'phone', 'city', 'address', 'notes',
        'subtotal', 'shipping_fee', 'total', 'payment_method', 'payment_status', 'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'payment_status' => PaymentStatus::class,
        ];
    }

    /** Les URLs publiques utilisent le numéro de commande plutôt que l'id. */
    public function getRouteKeyName(): string
    {
        return 'number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Chiffre d'affaires : on exclut les commandes annulées. */
    public function scopeCountable(Builder $query): Builder
    {
        return $query->where('status', '!=', OrderStatus::Cancelled->value);
    }

    /**
     * Génère un numéro lisible et unique : TS-AAAAMMJJ-XXXXX.
     */
    public static function generateNumber(): string
    {
        do {
            $number = 'TS-'.now()->format('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        } while (static::where('number', $number)->exists());

        return $number;
    }
}
