<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\OrderNotifier;
use App\Services\OrderService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private PaymentManager $payments,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $cart = $this->cart->summary();

        if ($cart->isEmpty()) {
            return to_route('cart.index')->with('warning', 'Votre panier est vide.');
        }

        return view('checkout.index', [
            'cart' => $cart,
            'user' => $request->user(),
            'paymentMethods' => $this->payments->available(),
        ]);
    }

    public function store(CheckoutRequest $request, OrderService $orders, OrderNotifier $notifier): RedirectResponse
    {
        try {
            $order = $orders->place($request->validated(), $request->user());
        } catch (ValidationException $e) {
            return to_route('cart.index')->with('error', collect($e->errors())->flatten()->first());
        }

        $result = $this->payments
            ->gateway(PaymentMethod::from($request->validated('payment_method')))
            ->process($order);

        $this->cart->clear();

        // E-mail de confirmation au client (+ alerte boutique)
        $notifier->orderPlaced($order);

        // Mémorise la commande en session pour autoriser l'accès à la confirmation (invités)
        $request->session()->push('placed_orders', $order->number);

        if ($result->redirectUrl) {
            return redirect()->away($result->redirectUrl);
        }

        return to_route('checkout.success', $order);
    }

    public function success(Request $request, Order $order): View
    {
        $ownsOrder = $request->user() && $order->user_id === $request->user()->id;
        $placedInSession = in_array($order->number, $request->session()->get('placed_orders', []), true);

        abort_unless($ownsOrder || $placedInSession, 404);

        return view('checkout.success', ['order' => $order->load('items.product.mainImage')]);
    }
}
