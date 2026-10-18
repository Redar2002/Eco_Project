<x-app-layout title="Commande confirmée">
    <div class="container-shop max-w-3xl py-14">
        <div class="text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                <x-icon name="check" class="h-8 w-8" stroke="2.5" />
            </div>
            <h1 class="mt-6 text-3xl font-bold tracking-tight sm:text-4xl">Merci pour votre commande !</h1>
            <p class="mt-3 text-ink-500">Votre commande a bien été enregistrée. Notre équipe vous appellera au <strong class="text-ink-900">{{ $order->phone }}</strong> pour la confirmer.</p>
            @if ($order->confirmation_sent_at)
                <p class="mt-3 text-sm text-ink-500">Un e-mail récapitulatif a été envoyé à <strong class="text-ink-900">{{ $order->email }}</strong>.</p>
            @elseif ($order->email)
                <p class="mt-3 text-sm text-ink-500">L'e-mail récapitulatif vous sera envoyé à <strong class="text-ink-900">{{ $order->email }}</strong> dans quelques instants.</p>
            @endif
            <div class="mt-6 inline-flex flex-col items-center rounded-2xl border border-dashed border-primary-200 bg-primary-50 px-8 py-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-primary-700">Numéro de commande</span>
                <span class="mt-1 font-display text-2xl font-bold tracking-wide text-primary">{{ $order->number }}</span>
            </div>
        </div>

        <div class="mt-12 overflow-hidden rounded-3xl border border-ink-100">
            <div class="grid gap-6 bg-ink-50 p-6 text-sm sm:grid-cols-3">
                <div>
                    <p class="text-ink-500">Livraison à</p>
                    <p class="mt-1 font-semibold text-ink-900">{{ $order->customer_name }}</p>
                    <p class="text-ink-600">{{ $order->address }}, {{ $order->city }}</p>
                </div>
                <div>
                    <p class="text-ink-500">Paiement</p>
                    <p class="mt-1 font-semibold text-ink-900">{{ $order->payment_method->label() }}</p>
                </div>
                <div>
                    <p class="text-ink-500">Statut</p>
                    <x-badge :color="$order->status->color()" class="mt-1">{{ $order->status->label() }}</x-badge>
                </div>
            </div>
            @include('partials.order-items', ['order' => $order])
        </div>

        <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
            <x-button :href="route('products.index')" icon-right="arrow-right">Continuer mes achats</x-button>
            @auth
                <x-button :href="route('account.orders.show', $order)" variant="outline">Suivre ma commande</x-button>
            @endauth
        </div>
    </div>
</x-app-layout>
