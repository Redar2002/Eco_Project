<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return tap(User::factory()->create())->forceFill(['role' => User::ROLE_ADMIN])->save() ? User::latest('id')->first() : null;
    }

    public function test_le_back_office_est_reserve_aux_admins(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($this->admin())->get(route('admin.dashboard'))->assertOk();
    }

    public function test_les_pages_admin_s_affichent(): void
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();
        $this->actingAs($this->admin());

        foreach ([
            route('admin.products.index'), route('admin.products.create'), route('admin.products.edit', $product),
            route('admin.categories.index'), route('admin.categories.create'), route('admin.categories.edit', $product->category),
            route('admin.orders.index'), route('admin.orders.index', ['status' => 'pending', 'q' => 'TS']), route('admin.orders.show', $order),
            route('admin.customers.index'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_creation_d_un_produit_avec_images(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $this->actingAs($this->admin())->post(route('admin.products.store'), [
            'name' => 'Hoodie Test',
            'category_id' => $category->id,
            'description' => 'Un hoodie de test.',
            'price' => 399,
            'sale_price' => 299,
            'stock' => 12,
            'is_active' => '1',
            'images' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.png')],
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'hoodie-test')->firstOrFail();
        $this->assertCount(2, $product->images);
        Storage::disk('public')->assertExists($product->images->first()->path);
    }

    public function test_le_prix_promo_doit_etre_inferieur_au_prix(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())->post(route('admin.products.store'), [
            'name' => 'Test', 'category_id' => $category->id, 'description' => 'x', 'price' => 100, 'sale_price' => 150, 'stock' => 1,
        ])->assertSessionHasErrors('sale_price');
    }

    public function test_annuler_une_commande_remet_le_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        $order = Order::factory()->create();
        $order->items()->create(['product_id' => $product->id, 'product_name' => $product->name, 'unit_price' => 100, 'quantity' => 2, 'line_total' => 200]);

        $this->actingAs($this->admin())
            ->patch(route('admin.orders.status', $order), ['status' => 'cancelled'])
            ->assertSessionHas('success');

        $this->assertSame(OrderStatus::Cancelled, $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_une_categorie_avec_produits_ne_peut_pas_etre_supprimee(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin())->delete(route('admin.categories.destroy', $product->category))->assertSessionHas('error');
        $this->assertModelExists($product->category);
    }
}
