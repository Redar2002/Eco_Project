<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogFilterRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Catalogue : filtres (catégorie, prix, promo), tri, recherche et pagination.
     */
    public function index(CatalogFilterRequest $request): View
    {
        $filters = $request->filters();

        $products = Product::active()
            ->with('mainImage', 'category')
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'filters' => $filters,
            'categories' => Category::ordered()->withCount(['products' => fn ($q) => $q->active()])->get(),
            'currentCategory' => isset($filters['categorie']) ? Category::where('slug', $filters['categorie'])->first() : null,
            'sortOptions' => [
                'nouveautes' => 'Nouveautés',
                'prix-asc' => 'Prix croissant',
                'prix-desc' => 'Prix décroissant',
                'nom' => 'Nom (A-Z)',
            ],
        ]);
    }

    /**
     * Fiche produit avec galerie et produits similaires.
     */
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('images', 'category');

        $similar = Product::active()
            ->with('mainImage', 'category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'similar'));
    }
}
