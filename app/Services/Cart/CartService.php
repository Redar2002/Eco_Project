<?php

namespace App\Services\Cart;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\ValidationException;

/**
 * Panier stocké en session.
 *
 * Sécurité : la session ne contient QUE des identifiants produits et des quantités.
 * Les prix, promotions et stocks sont toujours relus depuis la base de données.
 */
class CartService
{
    private const KEY = 'cart.items';

    public function __construct(private Session $session) {}

    /** @return array<int, int> product_id => quantité */
    public function raw(): array
    {
        return $this->session->get(self::KEY, []);
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    /**
     * Ajoute un produit (ou augmente sa quantité) en respectant le stock disponible.
     *
     * @throws ValidationException
     */
    public function add(Product $product, int $quantity = 1): int
    {
        $this->ensurePurchasable($product);

        $items = $this->raw();
        $wanted = ($items[$product->id] ?? 0) + $quantity;
        $max = $this->maxFor($product);

        if (($items[$product->id] ?? 0) >= $max) {
            throw ValidationException::withMessages([
                'quantity' => "Vous avez déjà la quantité maximale disponible pour « {$product->name} ».",
            ]);
        }

        $items[$product->id] = min($wanted, $max);
        $this->session->put(self::KEY, $items);

        return $items[$product->id];
    }

    /** Remplace la quantité d'un article (0 = suppression). */
    public function update(Product $product, int $quantity): int
    {
        if ($quantity <= 0) {
            $this->remove($product);

            return 0;
        }

        $this->ensurePurchasable($product);

        $items = $this->raw();
        $items[$product->id] = min($quantity, $this->maxFor($product));
        $this->session->put(self::KEY, $items);

        return $items[$product->id];
    }

    public function remove(Product $product): void
    {
        $items = $this->raw();
        unset($items[$product->id]);
        $this->session->put(self::KEY, $items);
    }

    public function clear(): void
    {
        $this->session->forget(self::KEY);
    }

    /**
     * Construit le récapitulatif à partir de la base de données.
     * Les produits supprimés/désactivés sont retirés et les quantités ajustées au stock.
     */
    public function summary(): CartSummary
    {
        $items = $this->raw();

        $products = Product::query()
            ->active()
            ->with('mainImage', 'category')
            ->whereIn('id', array_keys($items))
            ->get()
            ->keyBy('id');

        $clean = [];
        $lines = collect();

        foreach ($items as $productId => $quantity) {
            $product = $products->get($productId);
            if (! $product || $product->stock <= 0) {
                continue;
            }

            $quantity = min($quantity, $this->maxFor($product));
            $clean[$productId] = $quantity;
            $unitPrice = $product->current_price;

            $lines->push(new CartLine($product, $quantity, $unitPrice, round($unitPrice * $quantity, 2)));
        }

        // On resynchronise la session si des articles ont été retirés ou ajustés
        if ($clean !== $items) {
            $this->session->put(self::KEY, $clean);
        }

        $subtotal = round($lines->sum('lineTotal'), 2);
        $shipping = $this->shippingFor($subtotal, $lines->isEmpty());

        return new CartSummary($lines, $subtotal, $shipping, round($subtotal + $shipping, 2), array_sum($clean));
    }

    /** Frais de livraison : gratuits au-delà du seuil configuré. */
    public function shippingFor(float $subtotal, bool $empty = false): float
    {
        if ($empty || $subtotal >= config('shop.free_shipping_threshold')) {
            return 0.0;
        }

        return (float) config('shop.shipping_fee');
    }

    private function maxFor(Product $product): int
    {
        return max(0, min($product->stock, config('shop.max_quantity_per_item')));
    }

    /** @throws ValidationException */
    private function ensurePurchasable(Product $product): void
    {
        if (! $product->is_active || $product->stock <= 0) {
            throw ValidationException::withMessages([
                'quantity' => "Désolé, « {$product->name} » n'est plus disponible.",
            ]);
        }
    }
}
