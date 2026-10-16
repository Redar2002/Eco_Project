<x-admin-layout title="Produits">
    <x-slot name="actions">
        <x-button :href="route('admin.products.create')" size="sm" icon="plus">Nouveau produit</x-button>
    </x-slot>

    <x-admin.card :padding="false">
        <form method="GET" class="flex flex-wrap items-center gap-3 border-b border-ink-100 p-4">
            <div class="relative min-w-56 flex-1">
                <label for="q" class="sr-only">Rechercher</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit…" class="form-input pl-10">
            </div>
            <x-select name="category" :options="$categories" :value="request('category')" placeholder="Toutes les catégories" class="w-full sm:w-52" />
            <x-select name="status" :options="['active' => 'Actifs', 'inactive' => 'Inactifs', 'low' => 'Stock faible', 'out' => 'En rupture']" :value="request('status')" placeholder="Tous les statuts" class="w-full sm:w-44" />
            <x-button variant="secondary" icon="filter">Filtrer</x-button>
            @if (request()->hasAny(['q', 'category', 'status']))
                <x-button :href="route('admin.products.index')" variant="ghost">Effacer</x-button>
            @endif
        </form>

        @if ($products->isEmpty())
            <p class="px-5 py-12 text-center text-sm text-ink-500">Aucun produit trouvé.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-ink-50/70 text-xs uppercase tracking-wider text-ink-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Produit</th>
                            <th class="hidden px-5 py-3 font-semibold md:table-cell">Catégorie</th>
                            <th class="px-5 py-3 font-semibold">Prix</th>
                            <th class="px-5 py-3 font-semibold">Stock</th>
                            <th class="hidden px-5 py-3 font-semibold sm:table-cell">Statut</th>
                            <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @foreach ($products as $product)
                            <tr class="transition hover:bg-ink-50/60">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->image_url }}" alt="" class="h-12 w-10 shrink-0 rounded-lg bg-ink-100 object-cover">
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="font-semibold text-ink-900 hover:text-primary">{{ $product->name }}</a>
                                            @if ($product->is_featured)<p class="text-xs text-accent-600">★ Mis en avant</p>@endif
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden px-5 py-3 text-ink-600 md:table-cell">{{ $product->category->name }}</td>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <p class="font-semibold text-ink-900">{{ format_mad($product->current_price) }}</p>
                                    @if ($product->is_on_sale)<p class="text-xs text-ink-400 line-through">{{ format_mad($product->price) }}</p>@endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($product->stock === 0)
                                        <x-badge color="red">Rupture</x-badge>
                                    @elseif ($product->stock <= config('shop.low_stock_threshold'))
                                        <x-badge color="amber">{{ $product->stock }}</x-badge>
                                    @else
                                        <span class="font-medium text-ink-700">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="hidden px-5 py-3 sm:table-cell">
                                    <x-badge :color="$product->is_active ? 'green' : 'slate'">{{ $product->is_active ? 'Actif' : 'Inactif' }}</x-badge>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                    @if ($product->is_active)
                                        <a href="{{ route('products.show', $product) }}" target="_blank" class="inline-flex rounded-lg p-2 text-ink-500 hover:bg-ink-100 hover:text-ink-900" aria-label="Voir sur la boutique"><x-icon name="eye" class="h-4 w-4" /></a>
                                    @endif
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex rounded-lg p-2 text-ink-500 hover:bg-ink-100 hover:text-ink-900" aria-label="Modifier"><x-icon name="pencil" class="h-4 w-4" /></a>
                                    <x-confirm-delete :action="route('admin.products.destroy', $product)" :name="'delete-product-'.$product->id" icon-only
                                                      title="Supprimer ce produit ?" :message="'« '.$product->name.' » sera définitivement supprimé. Les commandes passées restent conservées.'" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.card>

    <div class="mt-6">{{ $products->links() }}</div>
</x-admin-layout>
