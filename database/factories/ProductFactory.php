<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Support\PlaceholderImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->paragraphs(2, true),
            'price' => fake()->randomElement([149, 199, 249, 299, 349, 449, 599, 799, 999]),
            'sale_price' => null,
            'stock' => fake()->numberBetween(5, 60),
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    /** Produit en promotion (-10 % à -40 %). */
    public function onSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_price' => round($attributes['price'] * fake()->randomElement([0.6, 0.7, 0.8, 0.9])),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    /** Ajoute des images placeholder SVG au produit. */
    public function withImages(int $count = 2, string $shape = 'tshirt'): static
    {
        return $this->afterCreating(function (Product $product) use ($count, $shape) {
            $colors = ['#1E3A8A', '#F59E0B', '#0F766E', '#BE123C', '#334155', '#7C3AED'];
            foreach (range(0, $count - 1) as $i) {
                $product->images()->create([
                    'path' => PlaceholderImage::make($product->name, $shape, $colors[$i % count($colors)]),
                    'position' => $i,
                ]);
            }
        });
    }
}
