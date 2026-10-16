<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Suivi de commande public : accessible avec le numéro + téléphone,
 * ou directement via le lien signé reçu par e-mail.
 */
class OrderTrackingController extends Controller
{
    public function create(): View
    {
        return view('orders.track');
    }

    public function lookup(TrackOrderRequest $request): RedirectResponse
    {
        $order = Order::where('number', $request->validated('number'))
            ->where('phone', $request->validated('phone'))
            ->first();

        if (! $order) {
            return back()->withInput()->withErrors([
                'number' => 'Aucune commande ne correspond à ce numéro et ce téléphone.',
            ]);
        }

        $request->session()->push('placed_orders', $order->number);

        return to_route('orders.track.show', $order);
    }

    public function show(Request $request, Order $order): View
    {
        $allowed = $request->hasValidSignature()
            || in_array($order->number, $request->session()->get('placed_orders', []), true)
            || ($request->user() && $request->user()->id === $order->user_id);

        abort_unless($allowed, 404);

        // Mémorise l'accès pour pouvoir recharger la page sans la signature
        if (! in_array($order->number, $request->session()->get('placed_orders', []), true)) {
            $request->session()->push('placed_orders', $order->number);
        }

        return view('orders.show', ['order' => $order->load('items.product.mainImage')]);
    }
}
