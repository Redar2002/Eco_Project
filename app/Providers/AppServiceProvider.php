<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(CartService::class, fn ($app) => new CartService($app['session.store']));
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.shop');

        // URLs en français pour les ressources : /creer et /modifier
        Route::resourceVerbs(['create' => 'creer', 'edit' => 'modifier']);

        // Accès au back-office réservé aux administrateurs
        Gate::define('access-admin', fn (User $user) => $user->isAdmin());

        // Données partagées par le layout de la boutique (menu + compteur panier)
        View::composer(['layouts.app', 'partials.header', 'partials.footer'], function ($view) {
            // On met en cache un simple tableau (les objets ne sont pas désérialisés par le cache)
            $categories = Cache::remember('nav.categories', 600, fn () => Category::ordered()->get(['name', 'slug'])->toArray());
            $view->with('navCategories', collect($categories)->map(fn (array $c) => (object) $c));
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
