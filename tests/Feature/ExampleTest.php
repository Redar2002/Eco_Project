<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérifie que les pages publiques s'affichent sans erreur.
 */
class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_publiques_repondent(): void
    {
        $product = Product::factory()->create();
        Product::factory()->inactive()->create(['slug' => 'produit-masque']);

        foreach (['/', '/boutique', '/boutique?categorie='.$product->category->slug.'&tri=prix-asc&min=10&max=5000', '/boutique?q=zzz', '/panier',
            '/a-propos', '/faq', '/cgv', '/contact', '/login', '/register', route('products.show', $product)] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/produit/produit-masque')->assertNotFound();
    }

    public function test_formulaire_de_contact(): void
    {
        $this->post(route('pages.contact.send'), [])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->post(route('pages.contact.send'), [
            'name' => 'Salma', 'email' => 'salma@example.ma', 'subject' => 'commande', 'message' => 'Bonjour, où en est ma commande ?',
        ])->assertRedirect(route('pages.contact'))->assertSessionHas('success');
    }

    public function test_inscription_newsletter(): void
    {
        $this->post(route('newsletter.store'), ['newsletter_email' => 'Fan@Example.ma'])->assertSessionHas('success');
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'fan@example.ma']);
    }
}
