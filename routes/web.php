<?php

use App\Http\Controllers\Account\DashboardController as AccountDashboardController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Boutique (public)
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/boutique', [ProductController::class, 'index'])->name('products.index');
Route::get('/produit/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Panier (stocké en session)
Route::controller(CartController::class)->prefix('panier')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{product}', 'store')->name('add');
    Route::patch('/{product}', 'update')->name('update');
    Route::delete('/{product}', 'destroy')->name('remove');
});

// Commande
Route::controller(CheckoutController::class)->prefix('commande')->name('checkout.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->middleware('throttle:10,1')->name('store');
    Route::get('/confirmation/{order}', 'success')->name('success');
});

// Suivi de commande (sans compte : numéro + téléphone, ou lien signé reçu par e-mail)
Route::controller(OrderTrackingController::class)->prefix('suivi-commande')->name('orders.track.')->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/', 'lookup')->middleware('throttle:10,1')->name('lookup');
    Route::get('/{order}', 'show')->name('show');
});

// Pages statiques
Route::controller(PageController::class)->name('pages.')->group(function () {
    Route::get('/a-propos', 'about')->name('about');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/cgv', 'cgv')->name('cgv');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->middleware('throttle:5,1')->name('contact.send');
});

Route::post('/newsletter', NewsletterController::class)->middleware('throttle:5,1')->name('newsletter.store');

/*
|--------------------------------------------------------------------------
| Espace client
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/mon-compte', AccountDashboardController::class)->name('dashboard');
    Route::get('/mon-compte/commandes', [AccountOrderController::class, 'index'])->name('account.orders.index');
    Route::get('/mon-compte/commandes/{order}', [AccountOrderController::class, 'show'])->name('account.orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Back-office (réservé au rôle admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('produits', Admin\ProductController::class)
        ->parameters(['produits' => 'product'])->except('show')->names('products');
    Route::delete('produits/{product}/images/{image}', [Admin\ProductController::class, 'destroyImage'])
        ->scopeBindings()->name('products.images.destroy');

    Route::resource('categories', Admin\CategoryController::class)->except('show');

    Route::get('commandes', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('commandes/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('commandes/{order}/statut', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('commandes/{order}/confirmation', [Admin\OrderController::class, 'resendConfirmation'])->name('orders.confirmation');

    Route::get('clients', [Admin\CustomerController::class, 'index'])->name('customers.index');
});

require __DIR__.'/auth.php';
