<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Chiffre d'affaires des 14 derniers jours (hors commandes annulées)
        $from = now()->subDays(13)->startOfDay();
        $daily = Order::countable()->where('created_at', '>=', $from)->get(['total', 'created_at'])
            ->groupBy(fn (Order $o) => $o->created_at->toDateString())
            ->map->sum('total');
        $chart = collect(range(0, 13))->map(function (int $i) use ($from, $daily) {
            $day = $from->copy()->addDays($i);

            return ['label' => $day->translatedFormat('d M'), 'short' => $day->format('d'), 'value' => (float) ($daily[$day->toDateString()] ?? 0)];
        });

        return view('admin.dashboard', [
            'stats' => [
                'revenue' => Order::countable()->sum('total'),
                'revenueMonth' => Order::countable()->where('created_at', '>=', now()->startOfMonth())->sum('total'),
                'orders' => Order::count(),
                'pending' => Order::where('status', OrderStatus::Pending)->count(),
                'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
                'outOfStock' => Product::where('stock', 0)->count(),
            ],
            'chart' => $chart,
            'chartMax' => max(1, $chart->max('value')),
            'latestOrders' => Order::latest()->take(8)->get(),
            'outOfStockProducts' => Product::with('mainImage')->where('stock', '<=', config('shop.low_stock_threshold'))->orderBy('stock')->take(6)->get(),
            'topProducts' => OrderItem::query()
                ->selectRaw('product_id, product_name, SUM(quantity) as sold, SUM(line_total) as revenue')
                ->whereHas('order', fn ($q) => $q->countable())
                ->groupBy('product_id', 'product_name')
                ->orderByDesc('sold')
                ->take(5)
                ->get(),
        ]);
    }
}
