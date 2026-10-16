<?php

namespace Tests\Feature;

use App\Mail\NewOrderAdmin;
use App\Mail\OrderConfirmed;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderEmailTest extends TestCase
{
    use RefreshDatabase;

    private function placeOrder(): Order
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->withSession(['cart.items' => [$product->id => 1]])->post(route('checkout.store'), [
            'customer_name' => 'Salma Bennani',
            'phone' => '0612345678',
            'email' => 'salma@example.ma',
            'city' => 'Rabat',
            'address' => '5 avenue Mohammed V, Agdal',
            'payment_method' => 'cod',
            'terms' => '1',
        ]);

        return Order::firstOrFail();
    }

    public function test_un_email_de_confirmation_est_envoye_au_client(): void
    {
        Mail::fake();
        config(['shop.admin_email' => 'boutique@example.ma']);

        $order = $this->placeOrder();

        Mail::assertSent(OrderConfirmed::class, fn (OrderConfirmed $mail) => $mail->hasTo('salma@example.ma') && $mail->order->is($order));
        Mail::assertSent(NewOrderAdmin::class, fn (NewOrderAdmin $mail) => $mail->hasTo('boutique@example.ma'));
    }

    public function test_le_contenu_de_l_email_de_confirmation(): void
    {
        $order = $this->placeOrder();
        $html = (new OrderConfirmed($order->load('items')))->render();

        $this->assertStringContainsString($order->number, $html);
        $this->assertStringContainsString(format_mad($order->total), $html);
        $this->assertStringContainsString('Suivre ma commande', $html);
    }

    public function test_l_email_est_obligatoire_au_checkout(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->withSession(['cart.items' => [$product->id => 1]])
            ->post(route('checkout.store'), ['email' => ''])
            ->assertSessionHasErrors('email');
    }

    public function test_le_client_est_prevenu_quand_l_admin_change_le_statut(): void
    {
        Mail::fake();
        $order = Order::factory()->create(['email' => 'client@example.ma']);
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->actingAs($admin)->patch(route('admin.orders.status', $order), ['status' => 'shipped', 'notify' => '1']);
        Mail::assertSent(OrderStatusUpdated::class, fn ($mail) => $mail->hasTo('client@example.ma'));

        // Case décochée : pas d'e-mail
        Mail::fake();
        $this->actingAs($admin)->patch(route('admin.orders.status', $order), ['status' => 'delivered', 'notify' => '0']);
        Mail::assertNothingSent();
    }

    public function test_suivi_par_numero_et_telephone(): void
    {
        $order = Order::factory()->create(['phone' => '0612345678']);

        $this->get(route('orders.track.show', $order))->assertNotFound();

        $this->post(route('orders.track.lookup'), ['number' => $order->number, 'phone' => '0699999999'])
            ->assertSessionHasErrors('number');

        $this->post(route('orders.track.lookup'), ['number' => strtolower($order->number), 'phone' => '06 12 34 56 78'])
            ->assertRedirect(route('orders.track.show', $order));

        $this->get(route('orders.track.show', $order))->assertOk()->assertSee($order->number);
    }

    public function test_le_lien_signe_de_l_email_donne_acces_au_suivi(): void
    {
        $order = Order::factory()->create();

        $this->get($order->trackingUrl())->assertOk()->assertSee($order->number);
        // Lien altéré, dans une nouvelle session : accès refusé
        $this->flushSession();
        $this->get($order->trackingUrl().'x')->assertNotFound();
    }
}
