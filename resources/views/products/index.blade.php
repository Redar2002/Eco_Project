@php
    $pageTitle = $currentCategory?->name ?? (isset($filters['q']) ? 'Résultats pour « '.$filters['q'].' »' : (isset($filters['promo']) ? 'Promotions' : 'Toute la boutique'));
    // Filtres actifs affichés sous forme de « chips » supprimables
    $chips = collect([
        'q' => isset($filters['q']) ? 'Recherche : '.$filters['q'] : null,
        'categorie' => $currentCategory?->name,
        'min' => isset($filters['min']) ? 'Min : '.format_mad($filters['min']) : null,
        'max' => isset($filters['max']) ? 'Max : '.format_mad($filters['max']) : null,
        'promo' => isset($filters['promo']) ? 'En promotion' : null,
    ])->filter();
@endphp

<x-app-layout :title="$pageTitle">
    <x-slot name="header">
        <x-breadcrumbs :items="$currentCategory ? ['Boutique' => route('products.index'), $currentCategory->name => null] : ['Boutique' => null]" />
        <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">{{ $pageTitle }}</h1>
        <p class="mt-2 text-ink-500">{{ $currentCategory?->description ?? 'Découvrez toute notre collection streetwear.' }}</p>
    </x-slot>

    <div x-data="{ filtersOpen: false }" class="container-shop py-10">
        <div class="lg:grid lg:grid-cols-[16rem_1fr] lg:gap-10">

            {{-- ===== Filtres ===== --}}
            <div x-show="filtersOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-ink-900/50 backdrop-blur-sm lg:hidden" @click="filtersOpen = false"></div>
            <aside :class="filtersOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-full max-w-xs -translate-x-full overflow-y-auto bg-white p-6 shadow-xl transition-transform duration-300 lg:static lg:z-auto lg:w-auto lg:max-w-none lg:translate-x-0 lg:overflow-visible lg:p-0 lg:shadow-none">
                <div class="mb-6 flex items-center justify-between lg:hidden">
                    <h2 class="text-lg font-semibold">Filtres</h2>
                    <button type="button" @click="filtersOpen = false" class="rounded-full p-2 hover:bg-ink-100" aria-label="Fermer les filtres"><x-icon name="x" /></button>
                </div>

                <form action="{{ route('products.index') }}" method="GET" class="space-y-8 lg:sticky lg:top-28">
                    @isset($filters['q'])<input type="hidden" name="q" value="{{ $filters['q'] }}">@endisset
                    @isset($filters['tri'])<input type="hidden" name="tri" value="{{ $filters['tri'] }}">@endisset

                    <div>
                        <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-ink-900">Catégories</h3>
                        <ul class="mt-4 space-y-1">
                            <li>
                                <label class="flex cursor-pointer items-center justify-between px-3 py-2 text-sm transition hover:bg-ink-50 has-[:checked]:bg-ivory has-[:checked]:font-semibold has-[:checked]:text-primary">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="categorie" value="" class="h-4 w-4 border-ink-300 text-primary focus:ring-accent" @checked(! isset($filters['categorie']))>
                                        Toutes
                                    </span>
                                </label>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <label class="flex cursor-pointer items-center justify-between px-3 py-2 text-sm transition hover:bg-ink-50 has-[:checked]:bg-ivory has-[:checked]:font-semibold has-[:checked]:text-primary">
                                        <span class="flex items-center gap-3">
                                            <input type="radio" name="categorie" value="{{ $category->slug }}" class="h-4 w-4 border-ink-300 text-primary focus:ring-accent" @checked(($filters['categorie'] ?? null) === $category->slug)>
                                            {{ $category->name }}
                                        </span>
                                        <span class="text-xs text-ink-400">{{ $category->products_count }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-ink-900">Prix (MAD)</h3>
                        <div class="mt-4 flex items-center gap-2">
                            <label class="sr-only" for="min">Prix minimum</label>
                            <input id="min" type="number" name="min" min="0" step="1" value="{{ $filters['min'] ?? '' }}" placeholder="Min" class="form-input">
                            <span class="text-ink-400">–</span>
                            <label class="sr-only" for="max">Prix maximum</label>
                            <input id="max" type="number" name="max" min="0" step="1" value="{{ $filters['max'] ?? '' }}" placeholder="Max" class="form-input">
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ([[null, 200], [200, 500], [500, null]] as [$from, $to])
                                <a href="{{ route('products.index', array_merge(\Illuminate\Support\Arr::except($filters, ['min', 'max']), array_filter(['min' => $from, 'max' => $to]))) }}"
                                   class=" border border-ink-200 px-3 py-1 text-xs text-ink-600 transition hover:border-ink-900 hover:text-ink-900">
                                    {{ $from ? ($to ? $from.' – '.$to : 'Plus de '.$from) : 'Moins de '.$to }} MAD
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-ink-700">
                            <input type="checkbox" name="promo" value="1" class="h-4 w-4 rounded-none border-ink-300 text-primary focus:ring-accent" @checked(isset($filters['promo']))>
                            Uniquement les promotions
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <x-button class="flex-1" icon="filter">Appliquer</x-button>
                        @if ($chips->isNotEmpty())
                            <x-button :href="route('products.index')" variant="ghost">Réinitialiser</x-button>
                        @endif
                    </div>
                </form>
            </aside>

            {{-- ===== Résultats ===== --}}
            <div>
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <p class="text-sm text-ink-500">
                        <span class="font-semibold text-ink-900">{{ $products->total() }}</span> {{ Str::plural('produit', $products->total()) }}
                    </p>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="filtersOpen = true" class="inline-flex items-center gap-2 border border-ink-200 px-4 py-2 text-sm font-medium lg:hidden">
                            <x-icon name="filter" class="h-4 w-4" /> Filtres
                            @if ($chips->isNotEmpty())<span class=" bg-primary px-1.5 text-xs text-white">{{ $chips->count() }}</span>@endif
                        </button>
                        <form action="{{ route('products.index') }}" method="GET" x-data>
                            @foreach (\Illuminate\Support\Arr::except($filters, 'tri') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <label for="tri" class="sr-only">Trier par</label>
                            <select id="tri" name="tri" @change="$el.form.submit()" class="border-ink-300 py-2.5 pl-4 pr-10 text-xs uppercase tracking-wider text-sm font-medium focus:border-accent focus:ring-accent">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['tri'] ?? 'nouveautes') === $value)>Trier : {{ $label }}</option>
                                @endforeach
                            </select>
                            <noscript><button type="submit" class="ml-2 text-sm underline">OK</button></noscript>
                        </form>
                    </div>
                </div>

                @if ($chips->isNotEmpty())
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($chips as $key => $label)
                            <a href="{{ route('products.index', \Illuminate\Support\Arr::except($filters, $key)) }}"
                               class="inline-flex items-center gap-1.5 border border-ink-200 bg-white py-1.5 pl-3 pr-2 text-xs font-medium text-ink-700 transition hover:bg-ink-200">
                                {{ $label }} <x-icon name="x" class="h-3.5 w-3.5" />
                            </a>
                        @endforeach
                    </div>
                @endif

                @if ($products->isEmpty())
                    <x-empty-state class="mt-8" icon="search" title="Aucun produit trouvé" description="Essayez de modifier vos filtres ou votre recherche.">
                        <x-button :href="route('products.index')" variant="outline">Voir tous les produits</x-button>
                    </x-empty-state>
                @else
                    <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-12">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
