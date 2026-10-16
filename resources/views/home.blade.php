<x-app-layout>
    {{-- ===== Hero ===== --}}
    <section class="container-shop pt-6 lg:pt-8">
        <div class="relative overflow-hidden rounded-[2rem] bg-primary-950 text-white">
            {{-- Décor --}}
            <div class="pointer-events-none absolute -right-32 -top-32 h-[28rem] w-[28rem] rounded-full bg-primary-600/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 left-1/3 h-80 w-80 rounded-full bg-accent/20 blur-3xl"></div>

            <div class="relative grid items-center gap-10 px-6 py-14 sm:px-12 lg:grid-cols-2 lg:py-20">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-accent-300 ring-1 ring-inset ring-white/15">
                        <x-icon name="sparkles" class="h-4 w-4" /> Nouvelle collection automne 2026
                    </span>
                    <h1 class="mt-6 text-4xl font-bold leading-[1.1] tracking-tight text-white sm:text-5xl xl:text-6xl">
                        Le style urbain,<br>
                        <span class="bg-gradient-to-r from-accent-300 to-accent bg-clip-text text-transparent">pensé au Maroc.</span>
                    </h1>
                    <p class="mt-6 max-w-lg text-lg text-primary-100/80">
                        Hoodies, sneakers et essentiels streetwear de qualité. Livrés chez vous en 24 à 72&nbsp;h, payés à la livraison.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <x-button :href="route('products.index')" variant="accent" size="lg" icon-right="arrow-right">Découvrir la boutique</x-button>
                        <x-button :href="route('products.index', ['promo' => 1])" variant="white" size="lg" class="!bg-white/10 !text-white ring-1 ring-inset ring-white/20 hover:!bg-white/20">Voir les promos</x-button>
                    </div>
                    <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-white/10 pt-8">
                        <div><dt class="text-xs text-primary-200/70">Clients satisfaits</dt><dd class="mt-1 font-display text-2xl font-semibold">+12k</dd></div>
                        <div><dt class="text-xs text-primary-200/70">Livraison</dt><dd class="mt-1 font-display text-2xl font-semibold">24-72h</dd></div>
                        <div><dt class="text-xs text-primary-200/70">Note moyenne</dt><dd class="mt-1 font-display text-2xl font-semibold">4,8/5</dd></div>
                    </dl>
                </div>

                {{-- Collage de produits --}}
                @if ($popular->count() >= 3)
                    <div class="relative hidden h-[30rem] lg:block">
                        <a href="{{ route('products.show', $popular[0]) }}" class="absolute right-0 top-0 h-[26rem] w-64 overflow-hidden rounded-3xl shadow-2xl ring-1 ring-white/10 transition duration-500 hover:-translate-y-2">
                            <img src="{{ $popular[0]->image_url }}" alt="{{ $popular[0]->name }}" class="h-full w-full object-cover">
                        </a>
                        <a href="{{ route('products.show', $popular[1]) }}" class="absolute bottom-0 left-8 h-72 w-52 overflow-hidden rounded-3xl shadow-2xl ring-1 ring-white/10 transition duration-500 hover:-translate-y-2">
                            <img src="{{ $popular[1]->image_url }}" alt="{{ $popular[1]->name }}" class="h-full w-full object-cover">
                        </a>
                        <div class="absolute bottom-10 right-10 flex items-center gap-3 rounded-2xl bg-white p-3 pr-5 text-ink-900 shadow-lift">
                            <img src="{{ $popular[2]->image_url }}" alt="" class="h-14 w-12 rounded-lg object-cover">
                            <div>
                                <p class="text-xs text-ink-500">Best-seller</p>
                                <p class="text-sm font-semibold">{{ $popular[2]->name }}</p>
                                <p class="text-sm font-bold text-primary">{{ format_mad($popular[2]->current_price) }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== Avantages ===== --}}
    <section class="container-shop mt-10">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['truck', 'Livraison rapide', 'Partout au Maroc en 24-72 h, offerte dès '.format_mad(config('shop.free_shipping_threshold')).'.'],
                ['cash', 'Paiement à la livraison', 'Payez en espèces à la réception, en toute confiance.'],
                ['refresh', 'Retours gratuits', 'Vous avez 14 jours pour changer d\'avis.'],
                ['chat', 'Service client', 'Une équipe disponible 7j/7 par téléphone et WhatsApp.'],
            ] as [$icon, $title, $text])
                <div class="flex flex-col gap-3 rounded-2xl border border-ink-100 p-5 transition hover:border-primary-100 hover:bg-primary-50/40 sm:flex-row sm:items-start">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary">
                        <x-icon :name="$icon" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-sans text-sm font-semibold">{{ $title }}</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-500 sm:text-sm">{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== Catégories en vedette ===== --}}
    <section class="container-shop mt-20">
        <x-section-title eyebrow="Explorer" title="Nos catégories" subtitle="Trouvez la pièce qui complète votre style." :href="route('products.index')" />
        <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['categorie' => $category->slug]) }}" class="group relative aspect-[3/4] overflow-hidden rounded-2xl bg-ink-100">
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 via-ink-950/10 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-4 text-white">
                        <h3 class="font-sans text-base font-semibold text-white">{{ $category->name }}</h3>
                        <p class="mt-0.5 flex items-center gap-1 text-xs text-white/70">
                            {{ $category->products_count }} produits
                            <x-icon name="arrow-right" class="h-3.5 w-3.5 transition group-hover:translate-x-1" />
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===== Produits populaires ===== --}}
    <section class="container-shop mt-20">
        <x-section-title eyebrow="Tendances" title="Les plus populaires" subtitle="Les pièces préférées de notre communauté." :href="route('products.index')" />
        <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
            @foreach ($popular as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- ===== Bannière promo ===== --}}
    @if ($onSale->isNotEmpty())
        <section class="container-shop mt-20">
            <div class="grid overflow-hidden rounded-[2rem] bg-accent-50 lg:grid-cols-5">
                <div class="flex flex-col justify-center p-8 sm:p-12 lg:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-[.2em] text-accent-700">Offres limitées</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Jusqu'à -40&nbsp;% sur une sélection</h2>
                    <p class="mt-4 text-ink-600">Profitez de nos meilleures promotions de la saison, dans la limite des stocks disponibles.</p>
                    <div class="mt-8"><x-button :href="route('products.index', ['promo' => 1])" icon-right="arrow-right">J'en profite</x-button></div>
                </div>
                <div class="grid grid-cols-2 gap-4 p-6 sm:p-8 lg:col-span-3 lg:grid-cols-4 lg:pl-0">
                    @foreach ($onSale as $product)
                        <a href="{{ route('products.show', $product) }}" class="group rounded-2xl bg-white p-2 shadow-soft transition hover:-translate-y-1 hover:shadow-lift">
                            <div class="relative aspect-square overflow-hidden rounded-xl bg-ink-100">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <x-badge color="accent" class="absolute left-2 top-2">-{{ $product->discount_percent }}%</x-badge>
                            </div>
                            <p class="mt-2 truncate px-1 text-xs font-semibold text-ink-900">{{ $product->name }}</p>
                            <x-price :product="$product" size="sm" class="px-1 pb-1" />
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== Nouveautés ===== --}}
    <section class="container-shop mt-20">
        <x-section-title eyebrow="Fraîchement arrivés" title="Nouveautés" subtitle="Les dernières pièces ajoutées à la collection." :href="route('products.index', ['tri' => 'nouveautes'])" />
        <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
            @foreach ($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
</x-app-layout>
