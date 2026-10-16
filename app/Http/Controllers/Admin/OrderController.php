<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
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

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order, OrderService $orders): RedirectResponse
    {
        $status = OrderStatus::from($request->validated('status'));

        try {
            $orders->updateStatus($order, $status);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', "Statut de la commande {$order->number} : {$status->label()}.");
    }
}
