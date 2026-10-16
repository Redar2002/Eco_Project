<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Yassine Amrani',
            'phone' => '06 12 34 56 78',
            'email' => 'yassine@example.ma',
            'city' => 'Casablanca',
            'address' => '12 rue des Orangers, Maârif',
            'payment_method' => 'cod',
            'terms' => '1',
        ], $overrides);
    }

    public function test_une_commande_est_creee_et_le_stock_decremente(): void
    {
        $product = Product::factory()->create(['price' => 300, 'sale_price' => 250, 'stock' => 5]);

        $response = $this->withSession(['cart.items' => [$product->id => 2]])
            ->post(route('checkout.store'), $this->validData());

        $order = Order::with('items')->firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));

        // Prix recalculés côté serveur : 2 × 250 = 500 → livraison offerte
        $this->assertEquals(500, $order->subtotal);
        $this->assertEquals(0, $order->shipping_fee);
        $this->assertEquals(500, $order->total);
        $this->assertSame('0612345678', $order->phone);
        $this->assertSame(OrderStatus::Pending, $order->status);
        $this->assertCount(1, $order->items);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertEmpty(session('cart.items'));

        $this->get(route('checkout.success', $order))->assertOk()->assertSee($order->number);
    }

    public function test_les_frais_de_livraison_sont_appliques_sous_le_seuil(): void
    {
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);

        $this->withSession(['cart.items' => [$product->id => 1]])
            ->post(route('checkout.store'), $this->validData());

        $order = Order::firstOrFail();
        $this->assertEquals(config('shop.shipping_fee'), $order->shipping_fee);
        $this->assertEquals(100 + config('shop.shipping_fee'), $order->total);
    }

    public function test_stock_insuffisant_annule_toute_la_commande(): void
    {
        $ok = Product::factory()->create(['stock' => 5]);
        $short = Product::factory()->create(['stock' => 1]);

        $this->withSession(['cart.items' => [$ok->id => 2, $short->id => 3]])
            ->post(route('checkout.store'), $this->validData())
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');

        $this->assertSame(0, Order::count());
        $this->assertSame(5, $ok->fresh()->stock); // transaction annulée
    }

    public function test_validation_du_formulaire(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->withSession(['cart.items' => [$product->id => 1]])
            ->post(route('checkout.store'), $this->validData(['phone' => '123', 'terms' => null, 'payment_method' => 'bitcoin']))
            ->assertSessionHasErrors(['phone', 'terms', 'payment_method']);

        $this->assertSame(0, Order::count());
    }

    public function test_checkout_redirige_si_panier_vide(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('cart.index'));
    }

    public function test_la_confirmation_est_privee(): void
    {
        $order = Order::factory()->create();

        $this->get(route('checkout.success', $order))->assertNotFound();
    }

    public function test_une_commande_client_est_rattachee_a_son_compte(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)
            ->withSession(['cart.items' => [$product->id => 1]])
            ->post(route('checkout.store'), $this->validData());

        $order = Order::firstOrFail();
        $this->assertSame($user->id, $order->user_id);
        $this->get(route('account.orders.index'))->assertOk()->assertSee($order->number);
        $this->get(route('account.orders.show', $order))->assertOk();

        // Un autre client ne peut pas voir cette commande
        $this->actingAs(User::factory()->create())->get(route('account.orders.show', $order))->assertNotFound();
    }
}
