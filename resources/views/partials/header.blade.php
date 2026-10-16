{{-- En-tête : bandeau défilant, navigation, logo centré, recherche, compte et panier --}}
@php
    $announcements = [
        'Livraison offerte dès '.format_mad(config('shop.free_shipping_threshold')),
        'Paiement à la livraison partout au Maroc',
        'Retours gratuits sous 14 jours',
        'Nouvelle collection automne 2026',
    ];
@endphp
<div x-data="{ mobileOpen: false, searchOpen: false, scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 40"
     @keydown.escape.window="mobileOpen = false; searchOpen = false">

    {{-- Bandeau d'annonces défilant --}}
    <div class="overflow-hidden bg-ink-950 text-[10px] font-medium uppercase tracking-[0.25em] text-ink-300" aria-label="Annonces">
        <div class="flex w-max animate-marquee py-2.5 hover:[animation-play-state:paused]">
            @foreach (range(1, 2) as $loopIndex)
                <div class="flex shrink-0 items-center" @if ($loopIndex === 2) aria-hidden="true" @endif>
                    @foreach ($announcements as $text)
                        <span class="px-8">{{ $text }}</span><span class="text-accent">✦</span>
                    @endforeach
                    @foreach ($announcements as $text)
                        <span class="px-8">{{ $text }}</span><span class="text-accent">✦</span>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <header class="sticky top-0 z-40 border-b bg-white/95 backdrop-blur-xl transition-shadow duration-300"
            :class="scrolled ? 'border-ink-200 shadow-soft' : 'border-ink-100'">
        <div class="container-shop grid h-16 grid-cols-[1fr_auto_1fr] items-center lg:h-20">
            {{-- Gauche : burger (mobile) + navigation (desktop) --}}
            <div class="flex items-center">
                <button type="button" @click="mobileOpen = true" class="-ml-2 p-2 text-ink-900 lg:hidden" aria-label="Ouvrir le menu">
                    <x-icon name="menu" class="h-6 w-6" />
                </button>

                <nav class="hidden items-center gap-8 lg:flex" aria-label="Navigation principale">
                    <a href="{{ route('products.index') }}" class="relative py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink-900 after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-accent after:transition-all hover:after:w-full">Boutique</a>

                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button type="button" @click="open = !open" :aria-expanded="open" class="flex items-center gap-1.5 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink-900">
                            Collections <x-icon name="chevron-down" class="h-3.5 w-3.5 transition" ::class="open && 'rotate-180'" />
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute left-0 top-full w-[34rem] pt-5">
                            <div class="grid grid-cols-[1fr_12rem] border border-ink-100 bg-white shadow-lift">
                                <div class="p-6">
                                    <p class="eyebrow">Catégories</p>
                                    <div class="mt-4 grid grid-cols-2 gap-x-6">
                                        @foreach ($navCategories as $navCategory)
                                            <a href="{{ route('products.index', ['categorie' => $navCategory->slug]) }}" class="group flex items-center justify-between border-b border-ink-100 py-3 text-sm text-ink-700 transition hover:text-ink-950">
                                                {{ $navCategory->name }} <x-icon name="arrow-right" class="h-3.5 w-3.5 -translate-x-2 text-accent opacity-0 transition group-hover:translate-x-0 group-hover:opacity-100" />
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <a href="{{ route('products.index', ['promo' => 1]) }}" class="flex flex-col justify-end bg-ink-950 p-6 text-white transition hover:bg-ink-900">
                                    <span class="font-display text-3xl italic text-accent">-40%</span>
                                    <span class="mt-1 text-sm text-ink-300">sur une sélection de pièces</span>
                                    <span class="mt-4 text-[10px] font-semibold uppercase tracking-[0.2em] text-white underline decoration-accent underline-offset-4">Découvrir</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('products.index', ['tri' => 'nouveautes']) }}" class="relative py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink-900 after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-accent after:transition-all hover:after:w-full">Nouveautés</a>
                    <a href="{{ route('products.index', ['promo' => 1]) }}" class="relative py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-rouge after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-rouge after:transition-all hover:after:w-full">Promos</a>
                </nav>
            </div>

            {{-- Centre : logo --}}
            <a href="{{ route('home') }}" class="justify-self-center" aria-label="Accueil TirrainStyle">
                <x-application-logo />
            </a>

            {{-- Droite : recherche, compte, panier --}}
            <div class="flex items-center justify-end gap-1 sm:gap-2">
                <button type="button" @click="searchOpen = !searchOpen" class="p-2 text-ink-900 transition hover:text-accent-700" aria-label="Rechercher" :aria-expanded="searchOpen">
                    <x-icon name="search" class="h-[22px] w-[22px]" />
                </button>

                @auth
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button type="button" @click="open = !open" :aria-expanded="open" class="p-2" aria-label="Mon compte">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full border border-accent bg-ink-950 font-display text-sm text-accent">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        </button>
                        <div x-show="open" x-cloak x-transition.origin.top.right class="absolute right-0 mt-3 w-64 border border-ink-100 bg-white shadow-lift">
                            <div class="border-b border-ink-100 bg-ivory px-5 py-4">
                                <p class="truncate font-display text-base text-ink-950">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-ink-500">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2">
                                @can('access-admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-accent-700 hover:bg-ivory"><x-icon name="chart" class="h-4 w-4" /> Back-office</a>
                                @endcan
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="user" class="h-4 w-4" /> Mon compte</a>
                                <a href="{{ route('account.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="clipboard" class="h-4 w-4" /> Mes commandes</a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="pencil" class="h-4 w-4" /> Mon profil</a>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-ink-100 p-2">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-3 py-2.5 text-sm text-ink-700 hover:bg-red-50 hover:text-rouge"><x-icon name="logout" class="h-4 w-4" /> Se déconnecter</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden p-2 text-ink-900 transition hover:text-accent-700 sm:block" aria-label="Se connecter">
                        <x-icon name="user" class="h-[22px] w-[22px]" />
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative p-2 text-ink-900 transition hover:text-accent-700" aria-label="Panier">
                    <x-icon name="bag" class="h-[22px] w-[22px]" />
                    <span x-data x-show="$store.cart.count > 0" x-text="$store.cart.count" @if ($cartCount === 0) x-cloak @endif
                          class="absolute right-0 top-0 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-accent px-1 text-[10px] font-bold text-ink-950">{{ $cartCount }}</span>
                </a>
            </div>
        </div>

        {{-- Panneau de recherche --}}
        <div x-show="searchOpen" x-cloak x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             @click.outside="searchOpen = false" class="absolute inset-x-0 top-full border-b border-ink-100 bg-white shadow-lift">
            <form action="{{ route('products.index') }}" method="GET" role="search" class="container-shop flex items-center gap-4 py-6">
                <x-icon name="search" class="h-6 w-6 text-accent" />
                <label for="header-search" class="sr-only">Rechercher un produit</label>
                <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un hoodie, des sneakers…" x-ref="search"
                       x-effect="searchOpen && $nextTick(() => $refs.search.focus())"
                       class="flex-1 border-0 border-b border-ink-200 bg-transparent px-0 py-3 font-display text-xl text-ink-950 placeholder:text-ink-300 focus:border-accent focus:ring-0 sm:text-2xl">
                <x-button size="sm" class="hidden sm:inline-flex">Rechercher</x-button>
            </form>
        </div>
    </header>

    {{-- Menu mobile (tiroir noir) --}}
    <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
        <div x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false" class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm"></div>
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="absolute inset-y-0 left-0 flex w-full max-w-sm flex-col overflow-y-auto bg-ink-950 text-white">
            <div class="flex h-16 items-center justify-between border-b border-white/10 px-5">
                <x-application-logo light />
                <button type="button" @click="mobileOpen = false" class="p-2 text-ink-400 hover:text-white" aria-label="Fermer le menu">
                    <x-icon name="x" class="h-6 w-6" />
                </button>
            </div>
            <nav class="flex-1 px-5 py-6" aria-label="Navigation mobile">
                @foreach ([[route('home'), 'Accueil'], [route('products.index'), 'Boutique'], [route('products.index', ['tri' => 'nouveautes']), 'Nouveautés']] as [$url, $label])
                    <a href="{{ $url }}" class="block border-b border-white/10 py-4 font-display text-2xl text-white transition hover:pl-2 hover:text-accent">{{ $label }}</a>
                @endforeach
                <a href="{{ route('products.index', ['promo' => 1]) }}" class="block border-b border-white/10 py-4 font-display text-2xl italic text-red-400 transition hover:pl-2">Promos</a>

                <p class="eyebrow mt-8 text-accent">Collections</p>
                <div class="mt-3 grid grid-cols-2 gap-x-4">
                    @foreach ($navCategories as $navCategory)
                        <a href="{{ route('products.index', ['categorie' => $navCategory->slug]) }}" class="py-2.5 text-sm text-ink-300 transition hover:text-accent">{{ $navCategory->name }}</a>
                    @endforeach
                </div>

                <p class="eyebrow mt-8 text-accent">Aide</p>
                <div class="mt-3 grid grid-cols-2 gap-x-4">
                    <a href="{{ route('orders.track.create') }}" class="py-2.5 text-sm text-ink-300 hover:text-accent">Suivre ma commande</a>
                    <a href="{{ route('pages.faq') }}" class="py-2.5 text-sm text-ink-300 hover:text-accent">FAQ</a>
                    <a href="{{ route('pages.contact') }}" class="py-2.5 text-sm text-ink-300 hover:text-accent">Contact</a>
                    <a href="{{ route('pages.about') }}" class="py-2.5 text-sm text-ink-300 hover:text-accent">À propos</a>
                </div>
            </nav>
            <div class="border-t border-white/10 p-5">
                @auth
                    <x-button :href="route('dashboard')" variant="white" class="w-full" icon="user">Mon compte</x-button>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <x-button :href="route('login')" variant="white" size="sm">Connexion</x-button>
                        <x-button :href="route('register')" variant="accent" size="sm">Inscription</x-button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
