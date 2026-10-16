<x-app-layout>
    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden bg-ink-950 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-[.06]" style="background-image: radial-gradient(#C9A24B 1px, transparent 1px); background-size: 26px 26px;"></div>
        <div class="pointer-events-none absolute -right-40 top-10 h-[34rem] w-[34rem] rounded-full bg-accent/15 blur-3xl"></div>
        <p class="pointer-events-none absolute -bottom-10 left-0 select-none font-display text-[16rem] italic leading-none text-white/[.03] lg:text-[22rem]" aria-hidden="true">Style</p>

        <div class="container-shop relative grid items-center gap-14 py-16 lg:grid-cols-[1.1fr_1fr] lg:py-24">
            <div x-reveal>
                <p class="eyebrow text-accent">Collection automne · hiver 2026</p>
                <h1 class="mt-6 text-5xl leading-[1.05] tracking-tight text-white sm:text-6xl xl:text-7xl">
                    L'élégance<br>
                    <span class="text-gold italic">de la rue.</span>
                </h1>
                <p class="mt-8 max-w-md text-lg leading-relaxed text-ink-300">
                    Des pièces streetwear d'exception, pensées à Casablanca. Livrées en 24 à 72&nbsp;h, réglées à la livraison.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <x-button :href="route('products.index')" variant="accent" size="lg" icon-right="arrow-right">Découvrir la collection</x-button>
                    <x-button :href="route('products.index', ['promo' => 1])" size="lg" class="!bg-transparent ring-1 ring-inset ring-white/30 hover:!bg-white hover:!text-ink-950">Les promotions</x-button>
                </div>
                <dl class="mt-14 grid max-w-lg grid-cols-3 divide-x divide-white/10 border-t border-white/10 pt-8">
                    @foreach ([['+12k', 'Clients'], ['24-72h', 'Livraison'], ['4,8/5', 'Satisfaction']] as [$value, $label])
                        <div class="px-4 first:pl-0">
                            <dd class="whitespace-nowrap font-display text-2xl text-accent sm:text-3xl">{{ $value }}</dd>
                            <dt class="mt-1 text-[10px] uppercase tracking-[0.2em] text-ink-400">{{ $label }}</dt>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Composition éditoriale --}}
            @if ($popular->count() >= 3)
                <div class="relative mx-auto hidden h-[34rem] w-full max-w-md lg:block" x-reveal="200">
                    <div class="absolute right-0 top-0 h-[28rem] w-72 translate-x-4 translate-y-4 border border-accent/60"></div>
                    <a href="{{ route('products.show', $popular[0]) }}" class="group absolute right-0 top-0 h-[28rem] w-72 overflow-hidden bg-ivory">
                        <img src="{{ $popular[0]->image_url }}" alt="{{ $popular[0]->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                    </a>
                    <a href="{{ route('products.show', $popular[1]) }}" class="group absolute bottom-0 left-0 h-64 w-48 overflow-hidden bg-ivory shadow-lift ring-8 ring-ink-950">
                        <img src="{{ $popular[1]->image_url }}" alt="{{ $popular[1]->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                    </a>
                    <a href="{{ route('products.show', $popular[2]) }}" class="absolute -left-6 top-16 flex items-center gap-4 border border-white/10 bg-ink-900/90 p-3 pr-6 backdrop-blur transition hover:border-accent">
                        <img src="{{ $popular[2]->image_url }}" alt="" class="h-16 w-14 bg-ivory object-cover">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-accent">Best-seller</p>
                            <p class="mt-1 font-display text-white">{{ $popular[2]->name }}</p>
                            <p class="text-sm text-ink-300">{{ format_mad($popular[2]->current_price) }}</p>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== AVANTAGES ===================== --}}
    <section class="border-b border-ink-100 bg-ivory">
        <div class="container-shop grid grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['truck', 'Livraison express', '24-72 h partout au Maroc'],
                ['cash', 'Paiement à la livraison', 'Réglez à la réception'],
                ['refresh', 'Retours offerts', '14 jours pour changer d\'avis'],
                ['shield', 'Qualité premium', 'Matières sélectionnées'],
            ] as $i => [$icon, $title, $text])
                <div class="flex items-center gap-4 px-2 py-7 sm:px-6 {{ $i > 0 ? 'lg:border-l lg:border-ink-200' : '' }}" x-reveal="{{ $i * 100 }}">
                    <x-icon :name="$icon" class="h-7 w-7 text-accent-600" stroke="1.2" />
                    <div>
                        <h3 class="font-sans text-[11px] font-semibold uppercase tracking-[0.16em] text-ink-950">{{ $title }}</h3>
                        <p class="mt-1 text-xs text-ink-500">{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===================== COLLECTIONS ===================== --}}
    <section class="container-shop mt-24">
        <x-section-title eyebrow="Explorer" title="Nos collections" subtitle="Six univers pour composer votre style, du basique essentiel à la pièce statement." :href="route('products.index')" link-label="Toute la boutique" x-reveal />
        <div class="mt-12 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['categorie' => $category->slug]) }}" x-reveal="{{ $loop->index * 80 }}"
                   @class([
                       'group relative overflow-hidden bg-ink-950',
                       'col-span-2 row-span-2 aspect-square lg:aspect-auto' => $loop->first,
                       'col-span-2 aspect-[2/1] lg:col-span-4 lg:aspect-[5/1]' => $loop->last && $loop->count === 6,
                       'aspect-[4/5]' => ! $loop->first && ! ($loop->last && $loop->count === 6),
                   ])>
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="" loading="lazy" class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-105 group-hover:opacity-100">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-ink-950/10 to-transparent"></div>
                    <div class="pointer-events-none absolute inset-3 border border-accent/0 transition duration-500 group-hover:border-accent/60"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-accent">{{ $category->products_count }} pièces</p>
                        <h3 @class(['mt-1 text-white', 'text-3xl sm:text-4xl' => $loop->first, 'text-lg sm:text-xl' => ! $loop->first])>{{ $category->name }}</h3>
                        <span class="mt-3 inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80 transition group-hover:text-accent">
                            Explorer <x-icon name="arrow-right" class="h-3.5 w-3.5 transition group-hover:translate-x-1" />
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===================== SÉLECTION (onglets) ===================== --}}
    <section class="container-shop mt-28" x-data="{ tab: 'popular' }">
        <div class="text-center" x-reveal>
            <p class="eyebrow justify-center">La sélection</p>
            <h2 class="mt-3 text-3xl tracking-tight sm:text-4xl">Pièces incontournables</h2>
            <div class="mt-8 inline-flex border border-ink-200 p-1" role="tablist">
                @foreach (['popular' => 'Les plus populaires', 'new' => 'Nouveautés'] as $key => $label)
                    <button type="button" role="tab" @click="tab = '{{ $key }}'" :aria-selected="tab === '{{ $key }}'"
                            :class="tab === '{{ $key }}' ? 'bg-ink-950 text-white' : 'text-ink-500 hover:text-ink-950'"
                            class="px-6 py-2.5 text-[11px] font-semibold uppercase tracking-[0.16em] transition">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        @foreach (['popular' => $popular, 'new' => $newArrivals] as $key => $list)
            <div x-show="tab === '{{ $key }}'" @if ($key !== 'popular') x-cloak @endif
                 x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-12 grid grid-cols-2 gap-x-4 gap-y-12 sm:gap-x-6 lg:grid-cols-4" role="tabpanel">
                @foreach ($list as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endforeach

        <div class="mt-14 text-center">
            <x-button :href="route('products.index')" variant="outline" icon-right="arrow-right">Voir toute la collection</x-button>
        </div>
    </section>

    {{-- ===================== SOLDES + COMPTE À REBOURS ===================== --}}
    @if ($onSale->isNotEmpty())
        <section class="mt-28 bg-ink-950 text-white">
            <div class="container-shop grid gap-12 py-20 lg:grid-cols-[1fr_1.3fr] lg:items-center">
                <div x-reveal
                     x-data="{
                        end: new Date({{ now()->endOfMonth()->timestamp * 1000 }}),
                        d: 0, h: 0, m: 0, s: 0,
                        tick() {
                            const diff = Math.max(0, this.end - new Date());
                            this.d = Math.floor(diff / 864e5); this.h = Math.floor(diff / 36e5) % 24;
                            this.m = Math.floor(diff / 6e4) % 60; this.s = Math.floor(diff / 1e3) % 60;
                        },
                     }" x-init="tick(); setInterval(() => tick(), 1000)">
                    <p class="eyebrow text-accent">Offre limitée</p>
                    <h2 class="mt-5 text-5xl leading-tight text-white sm:text-6xl">Soldes <span class="italic text-red-500">d'automne</span></h2>
                    <p class="mt-5 max-w-md text-ink-300">Jusqu'à <strong class="text-white">-40&nbsp;%</strong> sur une sélection de pièces, dans la limite des stocks disponibles.</p>

                    <div class="mt-8 flex gap-3" aria-label="Temps restant">
                        @foreach (['d' => 'Jours', 'h' => 'Heures', 'm' => 'Min', 's' => 'Sec'] as $unit => $label)
                            <div class="w-16 border border-white/15 py-3 text-center sm:w-20">
                                <span class="block font-display text-2xl text-accent sm:text-3xl" x-text="String({{ $unit }}).padStart(2, '0')">00</span>
                                <span class="text-[9px] uppercase tracking-[0.2em] text-ink-400">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10"><x-button :href="route('products.index', ['promo' => 1])" variant="accent" icon-right="arrow-right">J'en profite</x-button></div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ($onSale as $product)
                        <a href="{{ route('products.show', $product) }}" class="group relative block overflow-hidden bg-ivory" x-reveal="{{ $loop->index * 100 }}">
                            <div class="aspect-[4/5]">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            </div>
                            <x-badge color="promo" class="absolute left-0 top-3">-{{ $product->discount_percent }}%</x-badge>
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink-950/90 to-transparent p-4 pt-10">
                                <p class="truncate font-display text-white">{{ $product->name }}</p>
                                <p class="text-sm"><span class="font-semibold text-white">{{ format_mad($product->current_price) }}</span> <span class="text-xs text-ink-400 line-through">{{ format_mad($product->price) }}</span></p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== MANIFESTE ===================== --}}
    <section class="container-shop mt-28 text-center" x-reveal>
        <span class="font-display text-7xl leading-none text-accent">“</span>
        <blockquote class="mx-auto -mt-6 max-w-3xl font-display text-2xl italic leading-relaxed text-ink-950 sm:text-3xl">
            Nous créons des vêtements pour celles et ceux qui font de la rue leur scène : des coupes affirmées, des matières nobles, et ce détail qui fait la différence.
        </blockquote>
        <div class="gold-rule mx-auto mt-8"></div>
        <p class="mt-4 text-[11px] font-semibold uppercase tracking-[0.22em] text-ink-500">Les fondateurs de TirrainStyle</p>
    </section>

    {{-- ===================== AVIS CLIENTS ===================== --}}
    <section class="container-shop mt-24">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ([
                ['Yasmine, Casablanca', 'Qualité impeccable, le hoodie est encore plus beau en vrai. Livré en 48 h, payé à la livraison : parfait.'],
                ['Mehdi, Rabat', 'Les sneakers taillent bien et le service client m\'a appelé pour confirmer. Très pro, je recommande.'],
                ['Salma, Marrakech', 'Enfin une marque marocaine avec ce niveau de finition. Le suivi de commande par e-mail est top.'],
            ] as $i => [$author, $quote])
                <figure class="border border-ink-100 p-8 transition duration-500 hover:border-accent hover:shadow-gold" x-reveal="{{ $i * 120 }}">
                    <div class="flex gap-1 text-accent" aria-label="5 étoiles sur 5">
                        @foreach (range(1, 5) as $star)
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8L10 14.9l-5.2 2.7 1-5.8L1.5 7.7l5.9-.9L10 1.5z"/></svg>
                        @endforeach
                    </div>
                    <blockquote class="mt-5 text-ink-600">« {{ $quote }} »</blockquote>
                    <figcaption class="mt-6 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink-950">{{ $author }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>
</x-app-layout>
