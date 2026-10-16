{{-- En-tête sticky : barre d'annonce, logo, menu, recherche, compte et panier --}}
<div x-data="{ mobileOpen: false, searchOpen: false }" @keydown.escape.window="mobileOpen = false; searchOpen = false">
    {{-- Barre d'annonce --}}
    <div class="bg-primary-950 text-white">
        <div class="container-shop flex h-9 items-center justify-center gap-6 text-xs font-medium">
            <p class="flex items-center gap-2"><x-icon name="truck" class="h-4 w-4 text-accent" /> Livraison gratuite dès {{ format_mad(config('shop.free_shipping_threshold')) }}</p>
            <p class="hidden items-center gap-2 sm:flex"><x-icon name="cash" class="h-4 w-4 text-accent" /> Paiement à la livraison partout au Maroc</p>
            <p class="hidden items-center gap-2 lg:flex"><x-icon name="refresh" class="h-4 w-4 text-accent" /> Retours gratuits sous 14 jours</p>
        </div>
    </div>

    <header class="sticky top-0 z-40 border-b border-ink-100 bg-white/90 backdrop-blur-lg supports-[backdrop-filter]:bg-white/75">
        <div class="container-shop flex h-16 items-center gap-4 lg:h-20">
            {{-- Burger (mobile) --}}
            <button type="button" @click="mobileOpen = true" class="-ml-2 rounded-full p-2 text-ink-700 hover:bg-ink-100 lg:hidden" aria-label="Ouvrir le menu">
                <x-icon name="menu" class="h-6 w-6" />
            </button>

            <a href="{{ route('home') }}" class="shrink-0" aria-label="Accueil TirrainStyle">
                <x-application-logo />
            </a>

            {{-- Navigation principale (desktop) --}}
            <nav class="ml-8 hidden items-center gap-1 lg:flex" aria-label="Navigation principale">
                <a href="{{ route('products.index') }}" @class(['rounded-full px-4 py-2 text-sm font-medium transition hover:bg-ink-100', 'text-primary' => request()->routeIs('products.index') && ! request()->hasAny(['categorie', 'promo']), 'text-ink-700' => ! request()->routeIs('products.index')])>Boutique</a>

                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button type="button" @click="open = !open" :aria-expanded="open" class="flex items-center gap-1 rounded-full px-4 py-2 text-sm font-medium text-ink-700 transition hover:bg-ink-100">
                        Catégories <x-icon name="chevron-down" class="h-4 w-4 transition" ::class="open && 'rotate-180'" />
                    </button>
                    <div x-show="open" x-cloak x-transition.origin.top.left
                         class="absolute left-0 top-full w-64 pt-2">
                        <div class="rounded-2xl bg-white p-2 shadow-lift ring-1 ring-ink-900/5">
                            @foreach ($navCategories as $navCategory)
                                <a href="{{ route('products.index', ['categorie' => $navCategory->slug]) }}" class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm text-ink-700 transition hover:bg-ink-50 hover:text-primary">
                                    {{ $navCategory->name }} <x-icon name="chevron-right" class="h-4 w-4 text-ink-300" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('products.index', ['tri' => 'nouveautes']) }}" class="rounded-full px-4 py-2 text-sm font-medium text-ink-700 transition hover:bg-ink-100">Nouveautés</a>
                <a href="{{ route('products.index', ['promo' => 1]) }}" class="rounded-full px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">Promos</a>
                <a href="{{ route('pages.contact') }}" class="rounded-full px-4 py-2 text-sm font-medium text-ink-700 transition hover:bg-ink-100">Contact</a>
            </nav>

            <div class="ml-auto flex items-center gap-1 sm:gap-2">
                {{-- Recherche (desktop) --}}
                <form action="{{ route('products.index') }}" method="GET" role="search" class="relative hidden md:block">
                    <label for="header-search" class="sr-only">Rechercher un produit</label>
                    <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
                    <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…"
                           class="w-44 rounded-full border-0 bg-ink-100 py-2.5 pl-10 pr-4 text-sm placeholder:text-ink-400 transition-all focus:w-64 focus:bg-white focus:ring-2 focus:ring-primary-600/30 xl:w-56 xl:focus:w-72">
                </form>
                <button type="button" @click="searchOpen = !searchOpen" class="rounded-full p-2 text-ink-700 hover:bg-ink-100 md:hidden" aria-label="Rechercher">
                    <x-icon name="search" class="h-6 w-6" />
                </button>

                {{-- Compte --}}
                @auth
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button type="button" @click="open = !open" :aria-expanded="open" class="flex items-center gap-2 rounded-full p-2 text-ink-700 hover:bg-ink-100" aria-label="Mon compte">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary text-xs font-semibold text-white">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        </button>
                        <div x-show="open" x-cloak x-transition.origin.top.right class="absolute right-0 mt-2 w-60 rounded-2xl bg-white p-2 shadow-lift ring-1 ring-ink-900/5">
                            <div class="border-b border-ink-100 px-3 pb-3 pt-2">
                                <p class="truncate text-sm font-semibold text-ink-900">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-ink-500">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                @can('access-admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-primary hover:bg-primary-50"><x-icon name="chart" class="h-4 w-4" /> Back-office</a>
                                @endcan
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="user" class="h-4 w-4" /> Mon compte</a>
                                <a href="{{ route('account.orders.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="clipboard" class="h-4 w-4" /> Mes commandes</a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="pencil" class="h-4 w-4" /> Mon profil</a>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-ink-100 pt-1">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-ink-700 hover:bg-ink-50"><x-icon name="logout" class="h-4 w-4" /> Se déconnecter</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rounded-full p-2 text-ink-700 hover:bg-ink-100" aria-label="Se connecter">
                        <x-icon name="user" class="h-6 w-6" />
                    </a>
                @endauth

                {{-- Panier avec compteur --}}
                <a href="{{ route('cart.index') }}" class="relative rounded-full p-2 text-ink-700 hover:bg-ink-100" aria-label="Panier">
                    <x-icon name="bag" class="h-6 w-6" />
                    <span x-data x-show="$store.cart.count > 0" x-text="$store.cart.count" @if ($cartCount === 0) x-cloak @endif
                          class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-accent px-1 text-[11px] font-bold text-ink-900 ring-2 ring-white">{{ $cartCount }}</span>
                </a>
            </div>
        </div>

        {{-- Recherche (mobile) --}}
        <div x-show="searchOpen" x-cloak x-transition class="border-t border-ink-100 md:hidden">
            <form action="{{ route('products.index') }}" method="GET" role="search" class="container-shop py-3">
                <div class="relative">
                    <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit…" x-ref="mobileSearch"
                           x-effect="searchOpen && $nextTick(() => $refs.mobileSearch.focus())"
                           class="w-full rounded-full border-0 bg-ink-100 py-3 pl-10 pr-4 text-sm focus:bg-white focus:ring-2 focus:ring-primary-600/30">
                </div>
            </form>
        </div>
    </header>

    {{-- Menu mobile (tiroir) --}}
    <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
        <div x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false" class="absolute inset-0 bg-ink-900/50 backdrop-blur-sm"></div>
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="absolute inset-y-0 left-0 flex w-full max-w-xs flex-col overflow-y-auto bg-white shadow-xl">
            <div class="flex h-16 items-center justify-between border-b border-ink-100 px-4">
                <x-application-logo />
                <button type="button" @click="mobileOpen = false" class="rounded-full p-2 text-ink-500 hover:bg-ink-100" aria-label="Fermer le menu">
                    <x-icon name="x" class="h-6 w-6" />
                </button>
            </div>
            <nav class="flex-1 space-y-1 p-4" aria-label="Navigation mobile">
                <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 font-medium text-ink-900 hover:bg-ink-50"><x-icon name="home" /> Accueil</a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 font-medium text-ink-900 hover:bg-ink-50"><x-icon name="store" /> Boutique</a>
                <a href="{{ route('products.index', ['promo' => 1]) }}" class="flex items-center gap-3 rounded-xl px-3 py-3 font-medium text-red-600 hover:bg-red-50"><x-icon name="tag" /> Promos</a>
                <p class="px-3 pb-1 pt-5 text-xs font-semibold uppercase tracking-wider text-ink-400">Catégories</p>
                @foreach ($navCategories as $navCategory)
                    <a href="{{ route('products.index', ['categorie' => $navCategory->slug]) }}" class="flex items-center justify-between rounded-xl px-3 py-2.5 text-ink-700 hover:bg-ink-50">
                        {{ $navCategory->name }} <x-icon name="chevron-right" class="h-4 w-4 text-ink-300" />
                    </a>
                @endforeach
                <p class="px-3 pb-1 pt-5 text-xs font-semibold uppercase tracking-wider text-ink-400">Aide</p>
                <a href="{{ route('orders.track.create') }}" class="block rounded-xl px-3 py-2.5 text-ink-700 hover:bg-ink-50">Suivre ma commande</a>
                <a href="{{ route('pages.faq') }}" class="block rounded-xl px-3 py-2.5 text-ink-700 hover:bg-ink-50">FAQ</a>
                <a href="{{ route('pages.contact') }}" class="block rounded-xl px-3 py-2.5 text-ink-700 hover:bg-ink-50">Contact</a>
                <a href="{{ route('pages.about') }}" class="block rounded-xl px-3 py-2.5 text-ink-700 hover:bg-ink-50">À propos</a>
            </nav>
            <div class="border-t border-ink-100 p-4">
                @auth
                    <x-button :href="route('dashboard')" variant="secondary" class="w-full" icon="user">Mon compte</x-button>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <x-button :href="route('login')" variant="outline" size="sm">Connexion</x-button>
                        <x-button :href="route('register')" size="sm">Inscription</x-button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
