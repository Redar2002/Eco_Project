<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        return view('cart.index', ['cart' => $this->cart->summary()]);
    }

    /**
     * Ajout au panier : réponse JSON pour les appels AJAX (toast), redirection sinon.
     */
    public function store(AddToCartRequest $request, Product $product): JsonResponse|RedirectResponse
    {
        $quantity = $this->cart->add($product, $request->quantity());

        if ($request->expectsJson()) {
            return response()->json([
                'count' => $this->cart->count(),
                'quantity' => $request->quantity(),
                'product' => [
                    'name' => $product->name,
                    'image' => $product->image_url,
                    'price' => format_mad($product->current_price),
                ],
            ]);
        }

        return back()->with('success', "« {$product->name} » a été ajouté à votre panier ({$quantity}).");
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $wanted = (int) $request->validated('quantity');
        $quantity = $this->cart->update($product, $wanted);

        if ($quantity === 0) {
            return to_route('cart.index')->with('success', "« {$product->name} » a été retiré du panier.");
        }

        if ($quantity < $wanted) {
            return to_route('cart.index')->with('warning', "Quantité limitée à {$quantity} pour « {$product->name} » (stock disponible).");
        }

        return to_route('cart.index')->with('success', 'Panier mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return to_route('cart.index')->with('success', "« {$product->name} » a été retiré du panier.");
    }
}
