<footer class="mt-24 bg-ink-950 text-ink-300">
    {{-- Newsletter --}}
    <div class="border-b border-white/10">
        <div class="container-shop grid gap-8 py-14 lg:grid-cols-2 lg:items-center">
            <div>
                <h2 class="text-2xl font-semibold text-white sm:text-3xl">Rejoignez la communauté</h2>
                <p class="mt-2 text-ink-400">Nouveautés, ventes privées et -10&nbsp;% sur votre première commande.</p>
            </div>
            <form action="{{ route('newsletter.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row">
                @csrf
                <label for="newsletter-email" class="sr-only">Adresse e-mail</label>
                <input id="newsletter-email" type="email" name="newsletter_email" required placeholder="Votre adresse e-mail"
                       class="flex-1 rounded-full border-0 bg-white/10 px-5 py-3.5 text-sm text-white placeholder:text-ink-400 focus:bg-white/15 focus:ring-2 focus:ring-accent">
                <x-button variant="accent">S'inscrire</x-button>
            </form>
            @error('newsletter_email')<p class="text-sm text-red-400 lg:col-start-2">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="container-shop grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <x-application-logo light />
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-400">
                La mode urbaine pensée au Maroc. Des pièces de qualité, des coupes actuelles et une livraison rapide dans tout le Royaume.
            </p>
            <ul class="mt-6 space-y-2 text-sm">
                <li class="flex items-center gap-2"><x-icon name="phone" class="h-4 w-4 text-accent" /> {{ config('shop.contact.phone') }}</li>
                <li class="flex items-center gap-2"><x-icon name="mail" class="h-4 w-4 text-accent" /> {{ config('shop.contact.email') }}</li>
                <li class="flex items-center gap-2"><x-icon name="map-pin" class="h-4 w-4 text-accent" /> {{ config('shop.contact.address') }}</li>
            </ul>
        </div>

        <div>
            <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-white">Boutique</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                @foreach ($navCategories as $navCategory)
                    <li><a href="{{ route('products.index', ['categorie' => $navCategory->slug]) }}" class="transition hover:text-white">{{ $navCategory->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-white">Aide</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('pages.faq') }}" class="transition hover:text-white">FAQ</a></li>
                <li><a href="{{ route('pages.contact') }}" class="transition hover:text-white">Nous contacter</a></li>
                <li><a href="{{ route('pages.cgv') }}" class="transition hover:text-white">Conditions générales de vente</a></li>
                <li><a href="{{ route('account.orders.index') }}" class="transition hover:text-white">Suivre ma commande</a></li>
            </ul>
        </div>

        <div>
            <h3 class="font-sans text-sm font-semibold uppercase tracking-wider text-white">TirrainStyle</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('pages.about') }}" class="transition hover:text-white">À propos</a></li>
                <li><a href="{{ route('products.index', ['tri' => 'nouveautes']) }}" class="transition hover:text-white">Nouveautés</a></li>
                <li><a href="{{ route('products.index', ['promo' => 1]) }}" class="transition hover:text-white">Promotions</a></li>
            </ul>
            <div class="mt-6 flex gap-3">
                {{-- Réseaux sociaux --}}
                <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-white/20" aria-label="Instagram">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                </a>
                <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-white/20" aria-label="Facebook">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 8h3V4h-3c-2.8 0-4.5 1.7-4.5 4.5V11H7v4h2.5v9h4v-9h3l.5-4h-3.5V8.8c0-.5.3-.8.5-.8Z"/></svg>
                </a>
                <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition hover:bg-white/20" aria-label="TikTok">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 3c.4 2.3 1.9 3.9 4.5 4v3.4c-1.7 0-3.2-.5-4.5-1.4v6.6c0 3.4-2.7 6.1-6.1 6.1S4.3 19 4.3 15.6s2.7-6.1 6.1-6.1c.3 0 .7 0 1 .1v3.5c-.3-.1-.6-.2-1-.2-1.5 0-2.7 1.2-2.7 2.7s1.2 2.7 2.7 2.7 2.7-1.2 2.7-2.7V3h3.4Z"/></svg>
                </a>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-shop flex flex-col items-center justify-between gap-4 py-6 text-xs text-ink-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} TirrainStyle. Tous droits réservés.</p>
            <div class="flex items-center gap-2">
                <span class="rounded-md bg-white/10 px-2 py-1 font-semibold text-ink-300">Paiement à la livraison</span>
                <span class="rounded-md bg-white/10 px-2 py-1 font-semibold text-ink-300">Livraison 24-72 h</span>
            </div>
        </div>
    </div>
</footer>
