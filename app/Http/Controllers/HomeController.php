<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $base = Product::active()->with('images', 'category');

        return view('home', [
            'categories' => Category::where('is_featured', true)->ordered()
                ->withCount(['products' => fn ($q) => $q->active()])->take(6)->get(),
            // « Populaires » : produits mis en avant, puis les plus vendus
            'popular' => (clone $base)->withSum('orderItems as sold', 'quantity')
                ->orderByDesc('is_featured')->orderByDesc('sold')->take(8)->get(),
            'newArrivals' => (clone $base)->latest()->take(8)->get(),
            'onSale' => (clone $base)->whereNotNull('sale_price')->where('stock', '>', 0)->latest()->take(4)->get(),
        ]);
    }
}
