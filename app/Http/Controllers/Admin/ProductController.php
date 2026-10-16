<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('mainImage', 'category')
            ->when($request->string('q')->trim()->value(), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->integer('category'), fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->input('status'), fn ($q, $status) => match ($status) {
                'active' => $q->where('is_active', true),
                'inactive' => $q->where('is_active', false),
                'out' => $q->where('stock', 0),
                'low' => $q->whereBetween('stock', [1, config('shop.low_stock_threshold')]),
                default => $q,
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::ordered()->pluck('name', 'id'),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true, 'stock' => 0]),
            'categories' => Category::ordered()->pluck('name', 'id'),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request) {
            $product = Product::create(Arr::except($request->validated(), 'images'));
            $this->storeImages($request, $product);

            return $product;
        });

        return to_route('admin.products.index')->with('success', "Le produit « {$product->name} » a été créé.");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load('images'),
            'categories' => Category::ordered()->pluck('name', 'id'),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $product->update(Arr::except($request->validated(), 'images'));
            $this->storeImages($request, $product);
        });

        return to_route('admin.products.edit', $product)->with('success', 'Le produit a été mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $paths = $product->images->pluck('path')->all();
        $product->delete(); // les images en base sont supprimées en cascade
        Storage::disk('public')->delete($paths);

        return to_route('admin.products.index')->with('success', "Le produit « {$product->name} » a été supprimé.");
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Image supprimée.');
    }

    /** Enregistre les images téléversées sur le disque public. */
    private function storeImages(ProductRequest $request, Product $product): void
    {
        $position = (int) $product->images()->max('position') + 1;

        foreach ($request->file('images', []) as $file) {
            $product->images()->create([
                'path' => $file->store('products', 'public'),
                'position' => $position++,
            ]);
        }
    }
}
