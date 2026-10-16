<x-app-layout title="Suivre ma commande">
    <x-page-header eyebrow="Suivi" title="Où en est ma commande ?" subtitle="Saisissez votre numéro de commande et le téléphone utilisé lors de l'achat." />

    <section class="container-shop mt-10 max-w-xl">
        <div class="relative rounded-3xl border border-ink-100 bg-white p-8 shadow-lift sm:p-10">
            <form action="{{ route('orders.track.lookup') }}" method="POST" class="space-y-6">
                @csrf
                <x-input name="number" label="Numéro de commande" required placeholder="TS-20261016-AB12C" hint="Vous le trouverez dans l'e-mail de confirmation." />
                <x-input name="phone" type="tel" label="Téléphone" required placeholder="06 12 34 56 78" autocomplete="tel" />
                <x-button size="lg" class="w-full" icon="search">Suivre ma commande</x-button>
            </form>
            @auth
                <p class="mt-6 border-t border-ink-100 pt-6 text-center text-sm text-ink-500">
                    Vous êtes connecté(e) : retrouvez toutes vos commandes dans
                    <a href="{{ route('account.orders.index') }}" class="font-semibold text-primary hover:text-primary-950">votre espace</a>.
                </p>
            @endauth
        </div>
    </section>
</x-app-layout>
