<footer class="mt-28 bg-ink-950 text-ink-400">
    {{-- Newsletter --}}
    <div class="relative overflow-hidden border-b border-white/10">
        <div class="pointer-events-none absolute -left-20 top-0 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>
        <div class="container-shop relative grid gap-10 py-16 lg:grid-cols-2 lg:items-center">
            <div>
                <p class="eyebrow text-accent">Le cercle TirrainStyle</p>
                <h2 class="mt-4 text-3xl text-white sm:text-4xl">Rejoignez la communauté<span class="text-accent">.</span></h2>
                <p class="mt-3 text-ink-400">Avant-premières, ventes privées et <span class="text-accent">-10&nbsp;%</span> sur votre première commande.</p>
            </div>
            <div>
                <form action="{{ route('newsletter.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <label for="newsletter-email" class="sr-only">Adresse e-mail</label>
                    <input id="newsletter-email" type="email" name="newsletter_email" required placeholder="Votre adresse e-mail"
                           class="flex-1 border-0 border-b border-white/20 bg-transparent px-0 py-3.5 text-white placeholder:text-ink-500 focus:border-accent focus:ring-0">
                    <x-button variant="accent">S'inscrire</x-button>
                </form>
                @error('newsletter_email')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="container-shop grid gap-12 py-16 sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <x-application-logo light />
            <p class="mt-6 max-w-sm text-sm leading-relaxed">
                La mode urbaine pensée au Maroc. Des pièces de caractère, des matières durables et une livraison rapide dans tout le Royaume.
            </p>
            <ul class="mt-8 space-y-3 text-sm">
                <li class="flex items-center gap-3"><x-icon name="phone" class="h-4 w-4 text-accent" /> {{ config('shop.contact.phone') }}</li>
                <li class="flex items-center gap-3"><x-icon name="mail" class="h-4 w-4 text-accent" /> {{ config('shop.contact.email') }}</li>
                <li class="flex items-center gap-3"><x-icon name="map-pin" class="h-4 w-4 text-accent" /> {{ config('shop.contact.address') }}</li>
            </ul>
        </div>

        @foreach ([
            'Boutique' => $navCategories->map(fn ($c) => [route('products.index', ['categorie' => $c->slug]), $c->name])->all(),
            'Aide' => [
                [route('orders.track.create'), 'Suivre ma commande'],
                [route('pages.faq'), 'FAQ'],
                [route('pages.contact'), 'Nous contacter'],
                [route('pages.cgv'), 'Conditions générales de vente'],
            ],
            'La maison' => [
                [route('pages.about'), 'À propos'],
                [route('products.index', ['tri' => 'nouveautes']), 'Nouveautés'],
                [route('products.index', ['promo' => 1]), 'Promotions'],
            ],
        ] as $heading => $links)
            <div>
                <h3 class="font-sans text-[11px] font-semibold uppercase tracking-[0.22em] text-white">{{ $heading }}</h3>
                <div class="mt-3 h-px w-8 bg-accent"></div>
                <ul class="mt-5 space-y-3 text-sm">
                    @foreach ($links as [$url, $label])
                        <li><a href="{{ $url }}" class="transition hover:pl-1 hover:text-accent">{{ $label }}</a></li>
                    @endforeach
                </ul>
                @if ($loop->last)
                    <div class="mt-8 flex gap-3">
                        <a href="#" class="flex h-10 w-10 items-center justify-center border border-white/15 transition hover:border-accent hover:text-accent" aria-label="Instagram">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                        </a>
                        <a href="#" class="flex h-10 w-10 items-center justify-center border border-white/15 transition hover:border-accent hover:text-accent" aria-label="Facebook">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 8h3V4h-3c-2.8 0-4.5 1.7-4.5 4.5V11H7v4h2.5v9h4v-9h3l.5-4h-3.5V8.8c0-.5.3-.8.5-.8Z"/></svg>
                        </a>
                        <a href="#" class="flex h-10 w-10 items-center justify-center border border-white/15 transition hover:border-accent hover:text-accent" aria-label="TikTok">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 3c.4 2.3 1.9 3.9 4.5 4v3.4c-1.7 0-3.2-.5-4.5-1.4v6.6c0 3.4-2.7 6.1-6.1 6.1S4.3 19 4.3 15.6s2.7-6.1 6.1-6.1c.3 0 .7 0 1 .1v3.5c-.3-.1-.6-.2-1-.2-1.5 0-2.7 1.2-2.7 2.7s1.2 2.7 2.7 2.7 2.7-1.2 2.7-2.7V3h3.4Z"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="border-t border-white/10">
        <div class="container-shop flex flex-col items-center justify-between gap-4 py-6 text-[11px] uppercase tracking-[0.18em] text-ink-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} TirrainStyle — Fait au Maroc</p>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-2"><x-icon name="cash" class="h-4 w-4 text-accent" /> Paiement à la livraison</span>
                <span class="flex items-center gap-2"><x-icon name="truck" class="h-4 w-4 text-accent" /> 24-72 h</span>
            </div>
        </div>
    </div>
</footer>
