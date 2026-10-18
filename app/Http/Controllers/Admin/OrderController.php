<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderNotifier;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->withCount('items')
            ->when(OrderStatus::tryFrom((string) $request->input('status')), fn ($q, $status) => $q->where('status', $status))
            ->when($request->string('q')->trim()->value(), fn ($q, $s) => $q->where(fn ($q) => $q
                ->where('number', 'like', "%{$s}%")
                ->orWhere('customer_name', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%")))
            ->when($request->date('from'), fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date('to'), fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statusCounts' => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items.product.mainImage', 'user')]);
    }

    /**
     * Nouvelle tentative d'envoi de la confirmation (si le premier envoi a échoué).
     * Une confirmation déjà envoyée n'est jamais renvoyée.
     */
    public function resendConfirmation(Order $order, OrderNotifier $notifier): RedirectResponse
    {
        if ($order->confirmation_sent_at) {
            return back()->with('error', "La confirmation a déjà été envoyée le {$order->confirmation_sent_at->translatedFormat('d F Y à H:i')}.");
        }

        if (! $notifier->sendConfirmation($order)) {
            return back()->with('error', 'L\'envoi a de nouveau échoué. Vérifiez la configuration e-mail (MAIL_*) et le journal storage/logs/laravel.log.');
        }

        return back()->with('success', "E-mail de confirmation envoyé à {$order->email}.");
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order, OrderService $orders, OrderNotifier $notifier): RedirectResponse
    {
        $status = OrderStatus::from($request->validated('status'));
        $previous = $order->status;

        try {
            $orders->updateStatus($order, $status);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        if ($previous === $status) {
            return back()->with('success', 'Aucun changement de statut.');
        }

        // Le client est prévenu par e-mail (si l'option est cochée)
        if ($request->boolean('notify', true) && $order->email) {
            $notifier->statusChanged($order);

            return back()->with('success', "Statut : {$status->label()}. Le client a été informé par e-mail.");
        }

        return back()->with('success', "Statut de la commande {$order->number} : {$status->label()}.");
    }
}
