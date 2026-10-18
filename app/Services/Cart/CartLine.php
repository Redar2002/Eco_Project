<?php

namespace App\Services\Cart;

use App\Models\Product;

/**
 * Ligne du panier, toujours construite à partir des données fraîches de la base.
 */
final readonly class CartLine
{
    public function __construct(
        public Product $product,
        public int $quantity,
        public float $unitPrice,
        public float $lineTotal,
    ) {}
}
