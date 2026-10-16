<?php

namespace App\Services\Cart;

use Illuminate\Support\Collection;

/**
 * Récapitulatif du panier (lignes + montants), calculé côté serveur.
 */
final readonly class CartSummary
{
    /**
     * @param  Collection<int, CartLine>  $lines
     */
    public function __construct(
        public Collection $lines,
        public float $subtotal,
        public float $shippingFee,
        public float $total,
        public int $count,
    ) {}

    public function isEmpty(): bool
    {
        return $this->lines->isEmpty();
    }

    /** Montant restant pour bénéficier de la livraison gratuite. */
    public function remainingForFreeShipping(): float
    {
        return max(0, config('shop.free_shipping_threshold') - $this->subtotal);
    }

    /** Progression (0-100) vers la livraison gratuite. */
    public function freeShippingProgress(): int
    {
        $threshold = config('shop.free_shipping_threshold');

        return $threshold > 0 ? (int) min(100, round($this->subtotal / $threshold * 100)) : 100;
    }
}
