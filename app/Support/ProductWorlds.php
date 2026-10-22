<?php

namespace App\Support;

use App\Models\Product;

/**
 * Choisit les ambiances (dégradés, couleur du texte, particules) des sections
 * de la fiche produit : la première dépend de la catégorie, les suivantes s'enchaînent.
 */
class ProductWorlds
{
    /**
     * @return list<array{key: string, label: string, gradient: string, light: bool, particles: list<string>}>
     */
    public static function for(Product $product, int $count = 3): array
    {
        $worlds = config('shop.product_worlds');
        $keys = array_keys($worlds);

        $start = config('shop.category_worlds.'.$product->category?->slug) ?? $keys[$product->id % count($keys)];
        $offset = (int) array_search($start, $keys, true);

        $sequence = [];
        for ($i = 0; $i < $count; $i++) {
            $key = $keys[($offset + $i) % count($keys)];
            $sequence[] = ['key' => $key] + $worlds[$key];
        }

        return $sequence;
    }

    /**
     * Particules flottantes aléatoires (position, taille, durée, délai) pour une section.
     *
     * @param  list<string>  $colors
     * @return list<array{x: int, y: int, size: int, duration: int, delay: float, drift: int, color: string}>
     */
    public static function particles(array $colors, int $count = 25): array
    {
        return array_map(fn () => [
            'x' => random_int(0, 100),
            'y' => random_int(10, 100),
            'size' => random_int(2, 6),
            'duration' => random_int(15, 30),
            'delay' => random_int(0, 150) / -10, // négatif : les particules sont déjà en mouvement au chargement
            'drift' => random_int(-40, 40),
            'color' => $colors[array_rand($colors)],
        ], range(1, $count));
    }
}
