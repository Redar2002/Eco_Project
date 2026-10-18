<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Filtres du catalogue (prix min/max sur le prix effectif, promo inclus).
 */
class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();
        foreach ([['Cent', 100, null], ['Deux cents', 200, null], ['Promo 300 à 150', 300, 150], ['Quatre cents', 400, null], ['Mille', 1000, null]] as [$name, $price, $sale]) {
            Product::factory()->for($category)->create(['name' => $name, 'price' => $price, 'sale_price' => $sale]);
        }
    }

    /** @return list<string> */
    private function namesFor(array $query): array
    {
        return $this->get(route('products.index', $query))->assertOk()
            ->viewData('products')->pluck('name')->sort()->values()->all();
    }

    public function test_prix_minimum(): void
    {
        $this->assertSame(['Deux cents', 'Mille', 'Quatre cents'], $this->namesFor(['min' => 200]));
    }

    public function test_prix_maximum(): void
    {
        $this->assertSame(['Cent', 'Deux cents', 'Promo 300 à 150'], $this->namesFor(['max' => 200]));
    }

    public function test_fourchette_de_prix_avec_decimales(): void
    {
        $this->assertSame(['Deux cents', 'Quatre cents'], $this->namesFor(['min' => '150.50', 'max' => 450]));
    }

    public function test_le_prix_promo_est_utilise(): void
    {
        // Le produit à 300 MAD soldé à 150 MAD entre dans la tranche 100-160
        $this->assertSame(['Cent', 'Promo 300 à 150'], $this->namesFor(['min' => 100, 'max' => 160]));
    }

    public function test_valeur_invalide_ignoree(): void
    {
        $this->assertCount(5, $this->namesFor(['min' => 'abc']));
    }
}
