@php
    $images = $product->images->map(fn ($img) => $img->url)->values();
    if ($images->isEmpty()) {
        $images = collect([asset('images/placeholder.svg')]);
    }
    $maxQty = min($product->stock, config('shop.max_quantity_per_item'));
@endphp

<x-app-layout :title="$product->name" :description="Str::limit($product->description, 150)">
    <div class="container-shop py-8">
        <x-breadcrumbs :items="[
            'Boutique' => route('products.index'),
            $product->category->name => route('products.index', ['categorie' => $product->category->slug]),
            $product->name => null,
        ]" />

        <div class="mt-8 grid gap-10 lg:grid-cols-2 lg:gap-16">
            {{-- ===== Galerie ===== --}}
            <div x-data="gallery(@js($images))" class="flex flex-col-reverse gap-4 sm:flex-row">
                @if ($images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto scrollbar-none sm:w-20 sm:flex-col">
                        <template x-for="(src, i) in images" :key="i">
                            <button type="button" @click="select(i)" :aria-label="'Image ' + (i + 1)"
                                    :class="active === i ? 'ring-2 ring-primary ring-offset-2' : 'opacity-70 hover:opacity-100'"
                                    class="aspect-[4/5] w-16 shrink-0 overflow-hidden rounded-xl bg-ink-100 transition sm:w-full">
                                <img :src="src" alt="" class="h-full w-full object-cover">
                            </button>
                        </template>
                    </div>
                @endif

                <div class="relative flex-1">
                    <div class="group relative aspect-[4/5] cursor-zoom-in overflow-hidden rounded-3xl bg-ink-100" @click="zoom = true">
                        <template x-for="(src, i) in images" :key="i">
                            <img :src="src" alt="{{ $product->name }}" x-show="active === i"
                                 x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </template>
                        <noscript><img src="{{ $images[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover"></noscript>

                        <div class="pointer-events-none absolute left-4 top-4 flex flex-col items-start gap-2">
                            @if ($product->is_on_sale)<x-badge color="accent" class="text-sm">-{{ $product->discount_percent }}% Promo</x-badge>@endif
                            @if ($product->is_new)<x-badge color="dark" class="text-sm">Nouveau</x-badge>@endif
                        </div>
                    </div>

                    @if ($images->count() > 1)
                        <button type="button" @click.stop="prev()" class="absolute left-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 shadow-soft backdrop-blur transition hover:bg-white" aria-label="Image précédente">
                            <x-icon name="chevron-left" class="h-5 w-5" />
                        </button>
                        <button type="button" @click.stop="next()" class="absolute right-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 shadow-soft backdrop-blur transition hover:bg-white" aria-label="Image suivante">
                            <x-icon name="chevron-right" class="h-5 w-5" />
                        </button>
                    @endif
                </div>

                {{-- Zoom plein écran --}}
                <div x-show="zoom" x-cloak x-transition.opacity @keydown.escape.window="zoom = false" @keydown.arrow-right.window="zoom && next()" @keydown.arrow-left.window="zoom && prev()"
                     class="fixed inset-0 z-[70] flex items-center justify-center bg-ink-950/90 p-4" @click.self="zoom = false" role="dialog" aria-modal="true">
                    <button type="button" @click="zoom = false" class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" aria-label="Fermer"><x-icon name="x" class="h-6 w-6" /></button>
                    <img :src="images[active]" alt="{{ $product->name }}" class="max-h-full max-w-full rounded-2xl object-contain">
                </div>
            </div>

            {{-- ===== Informations ===== --}}
            <div class="lg:py-4">
                <a href="{{ route('products.index', ['categorie' => $product->category->slug]) }}" class="text-xs font-semibold uppercase tracking-[.2em] text-accent-600 hover:text-accent-700">{{ $product->category->name }}</a>
                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">{{ $product->name }}</h1>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <x-price :product="$product" size="lg" />
                    @if ($product->is_on_sale)
                        <x-badge color="red">Vous économisez {{ format_mad($product->price - $product->current_price) }}</x-badge>
                    @endif
                </div>

                {{-- Stock --}}
                <div class="mt-5">
                    @if ($product->stock > config('shop.low_stock_threshold'))
                        <p class="flex items-center gap-2 text-sm font-medium text-green-700"><span class="h-2 w-2 rounded-full bg-green-500"></span> En stock, expédié sous 24 h</p>
                    @elseif ($product->in_stock)
                        <p class="flex items-center gap-2 text-sm font-medium text-amber-700"><span class="h-2 w-2 animate-pulse rounded-full bg-amber-500"></span> Plus que {{ $product->stock }} en stock, faites vite !</p>
                    @else
                        <p class="flex items-center gap-2 text-sm font-medium text-red-600"><span class="h-2 w-2 rounded-full bg-red-500"></span> Rupture de stock</p>
                    @endif
                </div>

                {{-- Ajout au panier --}}
                @if ($product->in_stock)
                    <form action="{{ route('cart.add', $product) }}" method="POST" x-data="addToCart({{ $maxQty }})" @submit.prevent="submit" class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @csrf
                        <div class="flex items-center justify-between rounded-full border border-ink-200 p-1 sm:w-36">
                            <button type="button" @click="decrement" :disabled="quantity <= 1" class="flex h-10 w-10 items-center justify-center rounded-full text-ink-700 transition hover:bg-ink-100 disabled:opacity-40" aria-label="Diminuer la quantité"><x-icon name="minus" class="h-4 w-4" /></button>
                            <label for="quantity" class="sr-only">Quantité</label>
                            <input id="quantity" type="number" name="quantity" x-model.number="quantity" min="1" max="{{ $maxQty }}" value="1"
                                   class="w-10 border-0 p-0 text-center text-sm font-semibold focus:ring-0 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button" @click="increment" :disabled="quantity >= max" class="flex h-10 w-10 items-center justify-center rounded-full text-ink-700 transition hover:bg-ink-100 disabled:opacity-40" aria-label="Augmenter la quantité"><x-icon name="plus" class="h-4 w-4" /></button>
                        </div>
                        <x-button size="lg" class="flex-1" ::disabled="loading">
                            <x-icon name="bag" x-show="!loading" />
                            <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3"/></svg>
                            Ajouter au panier
                        </x-button>
                    </form>
                    @error('quantity')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                @else
                    <div class="mt-8">
                        <x-button size="lg" class="w-full" disabled>Indisponible pour le moment</x-button>
                    </div>
                @endif

                {{-- Réassurance --}}
                <ul class="mt-8 grid gap-3 rounded-2xl bg-ink-50 p-5 text-sm sm:grid-cols-2">
                    <li class="flex items-center gap-3"><x-icon name="truck" class="text-primary" /> Livraison 24-72 h au Maroc</li>
                    <li class="flex items-center gap-3"><x-icon name="cash" class="text-primary" /> Paiement à la livraison</li>
                    <li class="flex items-center gap-3"><x-icon name="refresh" class="text-primary" /> Retours gratuits 14 jours</li>
                    <li class="flex items-center gap-3"><x-icon name="shield" class="text-primary" /> Qualité garantie</li>
                </ul>

                {{-- Description / livraison (accordéon) --}}
                <div x-data="{ tab: 'description' }" class="mt-8 divide-y divide-ink-100 border-y border-ink-100">
                    <div>
                        <button type="button" @click="tab = tab === 'description' ? null : 'description'" class="flex w-full items-center justify-between py-4 text-left font-semibold text-ink-900">
                            Description <x-icon name="chevron-down" class="h-5 w-5 transition" ::class="tab === 'description' && 'rotate-180'" />
                        </button>
                        <div x-show="tab === 'description'" class="rich-text pb-5 text-sm">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    <div>
                        <button type="button" @click="tab = tab === 'shipping' ? null : 'shipping'" class="flex w-full items-center justify-between py-4 text-left font-semibold text-ink-900">
                            Livraison & retours <x-icon name="chevron-down" class="h-5 w-5 transition" ::class="tab === 'shipping' && 'rotate-180'" />
                        </button>
                        <div x-show="tab === 'shipping'" x-cloak class="rich-text pb-5 text-sm">
                            <p>Livraison en 24 à 72 h ouvrées dans tout le Maroc. Frais de livraison : {{ format_mad(config('shop.shipping_fee')) }}, offerts dès {{ format_mad(config('shop.free_shipping_threshold')) }} d'achat.</p>
                            <p>Vous disposez de 14 jours après réception pour retourner un article non porté, dans son emballage d'origine.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Produits similaires ===== --}}
        @if ($similar->isNotEmpty())
            <section class="mt-24">
                <x-section-title title="Vous aimerez aussi" :href="route('products.index', ['categorie' => $product->category->slug])" link-label="Voir la catégorie" />
                <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
                    @foreach ($similar as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
