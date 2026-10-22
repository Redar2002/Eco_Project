@php
    use App\Support\ProductWorlds;
    use Illuminate\Support\Str;

    $images = $product->images->map(fn ($img) => $img->url)->values();
    if ($images->isEmpty()) {
        $images = collect([asset('images/placeholder.svg')]);
    }
    $maxQty = min($product->stock, config('shop.max_quantity_per_item'));
    $worlds = ProductWorlds::for($product, 3);
    $fg = fn (array $w) => $w['light'] ? '#2A1810' : '#FFFFFF';
    $fgInv = fn (array $w) => $w['light'] ? '#FFFFFF' : '#2A1810';
    $paragraphs = collect(preg_split('/\R{2,}/', trim($product->description)))->filter()->values();
    $categoryWord = Str::before(Str::before($product->category->name, ' '), '&');
@endphp

<x-app-layout :title="$product->name" :description="Str::limit($product->description, 150)">
    @push('head')
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&display=swap" rel="stylesheet">
        {{-- Défilement « aimanté » sur les sections plein écran de cette page --}}
        <style>
            html { scroll-snap-type: y proximity; scroll-padding-top: 4rem; }
            @media (min-width: 1024px) { html { scroll-padding-top: 5rem; } }
        </style>
    @endpush

    <div x-data="productShowcase(@js($worlds))" class="relative isolate transition-colors duration-500"
         style="--fg: {{ $fg($worlds[0]) }}; --fg-inv: {{ $fgInv($worlds[0]) }}; color: var(--fg);"
         :style="`--fg: ${world.light ? '#2A1810' : '#FFFFFF'}; --fg-inv: ${world.light ? '#FFFFFF' : '#2A1810'}; color: var(--fg);`">

        {{-- ===== Fond : dégradés radiaux qui basculent selon la section active (0,2 s) ===== --}}
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="sticky top-0 h-[100dvh] w-full overflow-hidden">
                @foreach ($worlds as $i => $w)
                    <div class="absolute inset-0 transition-opacity duration-200 ease-in-out {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}"
                         style="background: {{ $w['gradient'] }};"
                         :class="current === {{ $i }} ? 'opacity-100' : 'opacity-0'"></div>
                @endforeach
            </div>
        </div>

        {{-- Navigation par points entre les sections --}}
        <nav x-show="visible" x-transition.opacity class="fixed right-4 top-1/2 z-30 hidden -translate-y-1/2 md:block lg:right-8" aria-label="Sections du produit">
            <div class="flex flex-col gap-3">
                @foreach (['Produit', 'Détails', 'Livraison'] as $i => $label)
                    <button type="button" @click="goTo({{ $i }})" :aria-current="current === {{ $i }}" title="{{ $label }}"
                            class="group flex items-center justify-end gap-3">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.25em] opacity-0 transition group-hover:opacity-80">{{ $label }}</span>
                        <span class="block h-2 rounded-full bg-[color:var(--fg)] transition-all duration-300"
                              :class="current === {{ $i }} ? 'w-8 opacity-100' : 'w-2 opacity-40'"></span>
                    </button>
                @endforeach
            </div>
        </nav>

        {{-- ================= Section 1 : le produit ================= --}}
        <section data-showcase-section="0" x-data="tilt3d" @mousemove="move($event)" @mouseleave="reset()"
                 class="relative flex min-h-[calc(100dvh-4rem)] snap-start items-center overflow-hidden lg:min-h-[calc(100dvh-5rem)]">
            @include('products.partials.particles', ['colors' => $worlds[0]['particles']])
            @include('products.partials.watermark', ['word' => $categoryWord])

            <div class="relative mx-auto flex w-full max-w-[1400px] flex-col items-center justify-between gap-12 px-6 py-12 md:flex-row md:gap-0 md:px-12">
                {{-- Image (gauche) : entrée, flottement et inclinaison 3D --}}
                <div x-data="gallery(@js($images))" class="showcase-media flex w-full flex-col items-center md:w-1/2">
                    <div class="relative" style="perspective: 1400px;">
                        <div class="showcase-float">
                            <div data-tilt class="relative transition-transform duration-75" style="transform-style: preserve-3d;">
                                <button type="button" @click="zoom = true" class="relative block aspect-[4/5] h-[50vh] cursor-zoom-in overflow-hidden rounded-[2rem] shadow-[0_40px_80px_-20px_rgba(0,0,0,0.55)] ring-1 ring-white/20 md:h-[72vh]" aria-label="Agrandir l'image">
                                    <template x-for="(src, i) in images" :key="i">
                                        <img :src="src" alt="{{ $product->name }}" x-show="active === i"
                                             x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute inset-0 h-full w-full object-cover">
                                    </template>
                                    <noscript><img src="{{ $images[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover"></noscript>
                                </button>
                                <div class="pointer-events-none absolute left-4 top-4 flex flex-col items-start gap-2" style="transform: translateZ(40px);">
                                    @if ($product->is_on_sale)<span class="rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-lg">-{{ $product->discount_percent }}%</span>@endif
                                    @if ($product->is_new)<span class="rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-[#2A1810] shadow-lg">Nouveau</span>@endif
                                </div>
                            </div>
                        </div>
                        {{-- Ombre portée au sol --}}
                        <div class="mx-auto mt-6 h-6 w-3/4 rounded-[50%] bg-black/30 blur-xl" aria-hidden="true"></div>
                    </div>

                    @if ($images->count() > 1)
                        <div class="mt-2 flex gap-3">
                            <template x-for="(src, i) in images" :key="i">
                                <button type="button" @click="select(i)" :aria-label="'Image ' + (i + 1)"
                                        :class="active === i ? 'ring-2 ring-[color:var(--fg)] opacity-100' : 'opacity-60 hover:opacity-100'"
                                        class="h-16 w-14 overflow-hidden rounded-xl ring-offset-2 ring-offset-transparent transition">
                                    <img :src="src" alt="" class="h-full w-full object-cover">
                                </button>
                            </template>
                        </div>
                    @endif

                    {{-- Zoom plein écran --}}
                    <div x-show="zoom" x-cloak x-transition.opacity @keydown.escape.window="zoom = false" @keydown.arrow-right.window="zoom && next()" @keydown.arrow-left.window="zoom && prev()"
                         class="fixed inset-0 z-[70] flex items-center justify-center bg-black/90 p-4" @click.self="zoom = false" role="dialog" aria-modal="true">
                        <button type="button" @click="zoom = false" class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" aria-label="Fermer"><x-icon name="x" class="h-6 w-6" /></button>
                        <img :src="images[active]" alt="{{ $product->name }}" class="max-h-full max-w-full rounded-2xl object-contain">
                    </div>
                </div>

                {{-- Texte (droite) --}}
                <div class="showcase-copy w-full md:w-[42%]">
                    <nav aria-label="Fil d'Ariane" class="text-xs opacity-70">
                        <a href="{{ route('home') }}" class="hover:opacity-100 hover:underline">Accueil</a>
                        <span class="mx-1.5">/</span>
                        <a href="{{ route('products.index', ['categorie' => $product->category->slug]) }}" class="hover:underline">{{ $product->category->name }}</a>
                    </nav>

                    <p class="mt-6 text-xs font-semibold uppercase tracking-[0.35em] opacity-80">{{ $product->category->name }}</p>
                    <h1 class="mt-3 font-serif-display text-4xl font-black leading-tight tracking-tight text-[color:var(--fg)] drop-shadow-lg md:text-6xl">{{ $product->name }}</h1>

                    <div class="mt-6 flex flex-wrap items-baseline gap-x-4 gap-y-2">
                        <span class="font-serif-display text-3xl font-bold md:text-4xl">{{ format_mad($product->current_price) }}</span>
                        @if ($product->is_on_sale)
                            <span class="text-lg line-through opacity-60">{{ format_mad($product->price) }}</span>
                            <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">Vous économisez {{ format_mad($product->price - $product->current_price) }}</span>
                        @endif
                    </div>

                    @if ($paragraphs->isNotEmpty())
                        <p class="mt-6 max-w-lg text-base font-light leading-relaxed opacity-90 drop-shadow-md md:text-lg">{{ $paragraphs->first() }}</p>
                    @endif

                    <p class="mt-6 flex items-center gap-2 text-sm font-medium">
                        @if ($product->stock > config('shop.low_stock_threshold'))
                            <span class="h-2 w-2 rounded-full bg-green-400 shadow-[0_0_8px_#4ade80]"></span> En stock, expédié sous 24 h
                        @elseif ($product->in_stock)
                            <span class="h-2 w-2 animate-pulse rounded-full bg-amber-400"></span> Plus que {{ $product->stock }} en stock, faites vite !
                        @else
                            <span class="h-2 w-2 rounded-full bg-red-500"></span> Rupture de stock
                        @endif
                    </p>

                    @if ($product->in_stock)
                        <form id="acheter" action="{{ route('cart.add', $product) }}" method="POST" x-data="addToCart({{ $maxQty }})" @submit.prevent="submit" class="mt-8 flex flex-col gap-3 sm:flex-row">
                            @csrf
                            <div class="flex items-center justify-between rounded-full border border-[color:var(--fg)]/30 p-1 backdrop-blur-sm sm:w-36">
                                <button type="button" @click="decrement" :disabled="quantity <= 1" class="flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-white/15 disabled:opacity-40" aria-label="Diminuer la quantité"><x-icon name="minus" class="h-4 w-4" /></button>
                                <label for="quantity" class="sr-only">Quantité</label>
                                <input id="quantity" type="number" name="quantity" x-model.number="quantity" min="1" max="{{ $maxQty }}" value="1"
                                       class="w-10 border-0 bg-transparent p-0 text-center text-sm font-semibold text-[color:var(--fg)] focus:ring-0 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" @click="increment" :disabled="quantity >= max" class="flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-white/15 disabled:opacity-40" aria-label="Augmenter la quantité"><x-icon name="plus" class="h-4 w-4" /></button>
                            </div>
                            <button type="submit" :disabled="loading"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-[color:var(--fg)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.15em] text-[color:var(--fg-inv)] shadow-xl transition duration-300 hover:scale-[1.02] hover:shadow-2xl active:scale-[.98] disabled:opacity-60">
                                <x-icon name="bag" class="h-5 w-5" x-show="!loading" />
                                <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3"/></svg>
                                Ajouter au panier
                            </button>
                        </form>
                        @error('quantity')<p class="mt-2 text-sm font-medium text-red-400">{{ $message }}</p>@enderror
                    @else
                        <p class="mt-8 inline-flex rounded-full border border-[color:var(--fg)]/30 px-8 py-4 text-sm font-semibold uppercase tracking-[0.15em] opacity-70">Indisponible pour le moment</p>
                    @endif

                    <ul class="mt-8 grid grid-cols-2 gap-3 text-xs opacity-85">
                        <li class="flex items-center gap-2"><x-icon name="truck" class="h-4 w-4" /> Livraison 24-72 h</li>
                        <li class="flex items-center gap-2"><x-icon name="cash" class="h-4 w-4" /> Paiement à la livraison</li>
                        <li class="flex items-center gap-2"><x-icon name="refresh" class="h-4 w-4" /> Retours 14 jours</li>
                        <li class="flex items-center gap-2"><x-icon name="shield" class="h-4 w-4" /> Qualité garantie</li>
                    </ul>
                </div>
            </div>

            <button type="button" @click="goTo(1)" class="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-1 text-[10px] uppercase tracking-[0.3em] opacity-70 transition hover:opacity-100 md:flex" aria-label="Voir les détails">
                Découvrir <x-icon name="chevron-down" class="h-5 w-5 animate-bounce" />
            </button>
        </section>

        {{-- ================= Section 2 : les détails ================= --}}
        <section data-showcase-section="1" x-data="tilt3d" @mousemove="move($event)" @mouseleave="reset()"
                 class="relative flex min-h-[calc(100dvh-4rem)] snap-start items-center overflow-hidden lg:min-h-[calc(100dvh-5rem)]">
            @include('products.partials.particles', ['colors' => $worlds[1]['particles']])
            @include('products.partials.watermark', ['word' => 'Détails'])

            <div class="relative mx-auto flex w-full max-w-[1400px] flex-col items-center justify-between gap-12 px-6 py-16 md:flex-row md:gap-0 md:px-12">
                <div class="showcase-media flex w-full justify-center md:w-1/2" style="perspective: 1400px;">
                    <div class="showcase-float">
                        <div data-tilt class="aspect-[4/5] h-[45vh] overflow-hidden rounded-[2rem] shadow-[0_40px_80px_-20px_rgba(0,0,0,0.55)] ring-1 ring-white/20 md:h-[64vh]" style="transform-style: preserve-3d;">
                            <img src="{{ $images[1] ?? $images[0] }}" alt="{{ $product->name }} — détail" loading="lazy" class="h-full w-full object-cover">
                        </div>
                    </div>
                </div>
                <div class="showcase-copy w-full md:w-[42%]">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] opacity-80">Le détail</p>
                    <h2 class="mt-3 font-serif-display text-4xl font-black leading-tight tracking-tight text-[color:var(--fg)] drop-shadow-lg md:text-6xl">Pensé jusque<br>dans les finitions.</h2>
                    <div class="mt-6 max-w-lg space-y-4 text-base font-light leading-relaxed opacity-90 drop-shadow-md md:text-lg">
                        @foreach ($paragraphs->skip(1)->whenEmpty(fn () => $paragraphs) as $paragraph)
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @endforeach
                    </div>
                    <dl class="mt-8 grid max-w-lg grid-cols-2 gap-px overflow-hidden rounded-2xl bg-[color:var(--fg)]/15 text-sm">
                        <div class="bg-black/10 p-4 backdrop-blur-sm"><dt class="text-xs uppercase tracking-[0.2em] opacity-70">Collection</dt><dd class="mt-1 font-semibold">{{ $product->category->name }}</dd></div>
                        <div class="bg-black/10 p-4 backdrop-blur-sm"><dt class="text-xs uppercase tracking-[0.2em] opacity-70">Disponibilité</dt><dd class="mt-1 font-semibold">{{ $product->in_stock ? 'En stock' : 'Épuisé' }}</dd></div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- ================= Section 3 : livraison ================= --}}
        <section data-showcase-section="2" x-data="tilt3d" @mousemove="move($event)" @mouseleave="reset()"
                 class="relative flex min-h-[calc(100dvh-4rem)] snap-start items-center overflow-hidden lg:min-h-[calc(100dvh-5rem)]">
            @include('products.partials.particles', ['colors' => $worlds[2]['particles']])
            @include('products.partials.watermark', ['word' => 'Maroc'])

            <div class="relative mx-auto flex w-full max-w-[1400px] flex-col items-center justify-between gap-12 px-6 py-16 md:flex-row md:gap-0 md:px-12">
                <div class="showcase-media flex w-full justify-center md:w-1/2" style="perspective: 1400px;">
                    <div class="showcase-float">
                        <div data-tilt class="aspect-[4/5] h-[45vh] overflow-hidden rounded-[2rem] shadow-[0_40px_80px_-20px_rgba(0,0,0,0.55)] ring-1 ring-white/20 md:h-[64vh]" style="transform-style: preserve-3d;">
                            <img src="{{ $images[2] ?? $images[0] }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover">
                        </div>
                    </div>
                </div>
                <div class="showcase-copy w-full md:w-[42%]">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] opacity-80">Livraison & retours</p>
                    <h2 class="mt-3 font-serif-display text-4xl font-black leading-tight tracking-tight text-[color:var(--fg)] drop-shadow-lg md:text-6xl">Livré chez vous.<br>Payé à la réception.</h2>
                    <ul class="mt-8 max-w-lg space-y-5 text-base font-light leading-relaxed md:text-lg">
                        <li class="flex gap-4"><x-icon name="truck" class="mt-1 h-6 w-6 shrink-0" /><span>Livraison en <strong class="font-semibold">24 à 72 h</strong> partout au Maroc : {{ format_mad(config('shop.shipping_fee')) }}, offerte dès {{ format_mad(config('shop.free_shipping_threshold')) }}.</span></li>
                        <li class="flex gap-4"><x-icon name="cash" class="mt-1 h-6 w-6 shrink-0" /><span>Vous ne payez rien en ligne : <strong class="font-semibold">paiement en espèces</strong> à la livraison.</span></li>
                        <li class="flex gap-4"><x-icon name="refresh" class="mt-1 h-6 w-6 shrink-0" /><span><strong class="font-semibold">14 jours</strong> pour changer d'avis, retours gratuits.</span></li>
                    </ul>
                    <div class="mt-10 flex flex-wrap gap-3">
                        @if ($product->in_stock)
                            <button type="button" @click="goTo(0)" class="inline-flex items-center gap-2 rounded-full bg-[color:var(--fg)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.15em] text-[color:var(--fg-inv)] shadow-xl transition hover:scale-[1.02]">
                                <x-icon name="bag" class="h-5 w-5" /> Commander
                            </button>
                        @endif
                        <a href="{{ route('pages.contact') }}" class="inline-flex items-center gap-2 rounded-full border border-[color:var(--fg)]/40 px-8 py-4 text-sm font-semibold uppercase tracking-[0.15em] transition hover:bg-white/10">Une question ?</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- ===== Produits similaires (fond clair classique) ===== --}}
    @if ($similar->isNotEmpty())
        <section class="relative z-10 bg-white pt-20">
            <div class="container-shop">
                <x-section-title title="Vous aimerez aussi" :href="route('products.index', ['categorie' => $product->category->slug])" link-label="Voir la catégorie" />
                <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
                    @foreach ($similar as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-app-layout>
