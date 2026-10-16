<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_produit_peut_etre_ajoute_au_panier_en_ajax(): void
    {
        $product = Product::factory()->create(['price' => 300, 'stock' => 5]);

        $this->postJson(route('cart.add', $product), ['quantity' => 2])
            ->assertOk()
            ->assertJson(['count' => 2, 'product' => ['name' => $product->name]]);

        $this->assertSame([$product->id => 2], session('cart.items'));
    }

    public function test_la_quantite_est_limitee_au_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $this->post(route('cart.add', $product), ['quantity' => 5])->assertRedirect();

        $this->assertSame(3, session('cart.items')[$product->id]);
    }

    public function test_un_produit_inactif_ou_en_rupture_ne_peut_pas_etre_ajoute(): void
    {
        $inactive = Product::factory()->inactive()->create();
        $soldOut = Product::factory()->outOfStock()->create();

        $this->postJson(route('cart.add', $inactive))->assertUnprocessable();
        $this->postJson(route('cart.add', $soldOut))->assertUnprocessable();
    }

    public function test_le_panier_affiche_les_prix_de_la_base_et_les_frais_de_livraison(): void
    {
        $product = Product::factory()->create(['price' => 200, 'sale_price' => 150, 'stock' => 10]);

        $this->withSession(['cart.items' => [$product->id => 2]])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee(format_mad(300), false)          // sous-total = 2 × 150 (prix promo)
            ->assertSee(format_mad(config('shop.shipping_fee')), false)
            ->assertSee(format_mad(300 + config('shop.shipping_fee')), false);
    }

    public function test_quantite_modifiable_et_article_supprimable(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $this->withSession(['cart.items' => [$product->id => 1]]);

        $this->patch(route('cart.update', $product), ['quantity' => 4])->assertRedirect(route('cart.index'));
        $this->assertSame(4, session('cart.items')[$product->id]);

        $this->delete(route('cart.remove', $product))->assertRedirect(route('cart.index'));
        $this->assertSame([], session('cart.items'));
    }
}
