<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

/**
 * E-mail de confirmation envoyé après la validation d'une commande.
 */
class OrderConfirmationEmailTest extends TestCase
{
    use RefreshDatabase;

    private function checkout(array $cart, array $overrides = [])
    {
        return $this->withSession(['cart.items' => $cart])->post(route('checkout.store'), array_merge([
            'customer_name' => 'Salma Bennani',
            'phone' => '0612345678',
            'email' => 'salma@example.ma',
            'city' => 'Rabat',
            'address' => '5 avenue Mohammed V, Agdal',
            'payment_method' => 'cod',
            'terms' => '1',
        ], $overrides));
    }

    private function orderWithItem(array $attributes = []): Order
    {
        $order = Order::factory()->create(array_merge(['email' => 'client@example.ma'], $attributes));
        $order->items()->create(['product_name' => 'Hoodie Test', 'unit_price' => 299, 'quantity' => 1, 'line_total' => 299]);

        return $order;
    }

    public function test_l_email_est_envoye_une_fois_la_commande_enregistree(): void
    {
        Mail::fake();
        $product = Product::factory()->create(['stock' => 5]);

        $this->checkout([$product->id => 2])->assertRedirect();

        $order = Order::firstOrFail();
        Mail::assertSent(OrderConfirmed::class, 1);
        Mail::assertSent(OrderConfirmed::class, fn (OrderConfirmed $mail) => $mail->hasTo('salma@example.ma')
            && $mail->order->is($order)
            && $mail->envelope()->subject === "Confirmation de votre commande n° {$order->number}");
        $this->assertNotNull($order->confirmation_sent_at);
    }

    public function test_aucun_email_si_la_commande_n_est_pas_enregistree(): void
    {
        Mail::fake();
        $product = Product::factory()->create(['stock' => 1]);

        // Stock insuffisant : la transaction est annulée
        $this->checkout([$product->id => 3])->assertRedirect(route('cart.index'));
        // Formulaire invalide : la commande n'est même pas tentée
        $this->checkout([$product->id => 1], ['email' => 'pas-une-adresse'])->assertSessionHasErrors('email');

        $this->assertSame(0, Order::count());
        Mail::assertNothingSent();
    }

    public function test_un_echec_d_envoi_conserve_la_commande_et_journalise_l_erreur(): void
    {
        Log::spy();
        Mail::shouldReceive('to')->andThrow(new RuntimeException('Connexion SMTP refusée'));
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->checkout([$product->id => 1]);

        $order = Order::firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));      // le client voit sa confirmation à l'écran
        $this->assertNull($order->confirmation_sent_at);                    // prête pour une nouvelle tentative
        $this->assertSame(4, $product->fresh()->stock);                     // la commande n'est pas annulée
        Log::shouldHaveReceived('error')->withArgs(fn ($message, $context) => str_contains($message, 'confirmation')
            && $context['order'] === $order->number
            && str_contains($context['error'], 'SMTP'));
    }

    public function test_la_confirmation_n_est_jamais_envoyee_deux_fois(): void
    {
        Mail::fake();
        $order = $this->orderWithItem();
        $notifier = app(OrderNotifier::class);

        $this->assertTrue($notifier->sendConfirmation($order));
        $this->assertFalse($notifier->sendConfirmation($order));            // même instance
        $this->assertFalse($notifier->sendConfirmation($order->fresh()));   // relu depuis la base
        $this->artisan('orders:send-confirmations')->assertSuccessful();    // relance planifiée

        Mail::assertSent(OrderConfirmed::class, 1);
    }

    public function test_relance_apres_echec_via_la_commande_artisan_sans_doublon_de_commande(): void
    {
        $order = $this->orderWithItem();
        Mail::fake();

        $this->artisan('orders:send-confirmations')->assertSuccessful();
        $this->artisan('orders:send-confirmations')->expectsOutputToContain('Aucune confirmation')->assertSuccessful();

        Mail::assertSent(OrderConfirmed::class, 1);
        $this->assertNotNull($order->fresh()->confirmation_sent_at);
        $this->assertSame(1, Order::count());
    }

    public function test_relance_manuelle_depuis_le_back_office(): void
    {
        Mail::fake();
        $order = $this->orderWithItem();
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->actingAs($admin)->post(route('admin.orders.confirmation', $order))->assertSessionHas('success');
        $this->actingAs($admin)->post(route('admin.orders.confirmation', $order))->assertSessionHas('error');

        Mail::assertSent(OrderConfirmed::class, 1);

        // Réservé aux administrateurs
        $this->actingAs(User::factory()->create())->post(route('admin.orders.confirmation', $order))->assertForbidden();
    }

    public function test_pas_d_envoi_sans_adresse_valide(): void
    {
        Mail::fake();
        $order = $this->orderWithItem(['email' => null]);

        $this->assertFalse(app(OrderNotifier::class)->sendConfirmation($order));
        Mail::assertNothingSent();
    }

    public function test_contenu_html_et_texte_de_l_email(): void
    {
        $product = Product::factory()->create(['name' => 'Hoodie Heavyweight Rif', 'price' => 449, 'sale_price' => 379, 'stock' => 5]);
        Mail::fake();
        $this->checkout([$product->id => 2]);
        $order = Order::with('items')->firstOrFail();

        $mail = new OrderConfirmed($order);
        $expected = [
            'Salma Bennani', $order->number, $order->created_at->translatedFormat('d F Y'),
            'Hoodie Heavyweight Rif', format_mad(379), format_mad(758),        // produit, prix unitaire, ligne
            format_mad($order->subtotal), format_mad($order->total), 'Offerte', // montants (livraison offerte)
            '5 avenue Mohammed V, Agdal', 'Rabat', 'Paiement à la livraison', 'En attente',
            config('shop.contact.phone'), config('shop.contact.email'),
        ];

        foreach ($expected as $text) {
            $mail->assertSeeInHtml($text, false);
            $mail->assertSeeInText($text, false);
        }
        $mail->assertSeeInHtml('Suivre ma commande');
        $mail->assertSeeInText('2 x');
    }
}
