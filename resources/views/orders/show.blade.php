<x-app-layout :title="'Suivi de la commande '.$order->number">
    <x-page-header eyebrow="Suivi de commande" :title="$order->number" :subtitle="'Passée le '.$order->created_at->translatedFormat('d F Y à H:i').' par '.$order->customer_name" />

    <section class="container-shop mt-10 max-w-5xl">
        <div class="relative rounded-3xl border border-ink-100 bg-white p-6 shadow-lift sm:p-10">
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-ink-500">Statut actuel</p>
                    <p class="mt-1 font-display text-2xl font-semibold text-ink-900">{{ $order->status->label() }}</p>
                </div>
                <p class="text-sm text-ink-500">Dernière mise à jour : {{ $order->updated_at->translatedFormat('d F Y à H:i') }}</p>
            </div>
            @include('partials.order-timeline', ['order' => $order])
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-3">
            <div class="overflow-hidden rounded-3xl border border-ink-100 lg:col-span-2">
                <h2 class="border-b border-ink-100 px-6 py-4 font-sans text-base font-semibold text-ink-900">Articles</h2>
                @include('partials.order-items', ['order' => $order])
            </div>
            <div class="space-y-6 text-sm">
                <div class="rounded-3xl border border-ink-100 p-6">
                    <h2 class="font-sans text-base font-semibold text-ink-900">Livraison</h2>
                    <p class="mt-4 font-medium text-ink-950">{{ $order->customer_name }}</p>
                    <p class="text-ink-600">{{ $order->address }}, {{ $order->city }}</p>
                    <p class="mt-2 text-ink-600">{{ $order->phone }}</p>
                </div>
                <div class="rounded-3xl border border-ink-100 p-6">
                    <h2 class="font-sans text-base font-semibold text-ink-900">Paiement</h2>
                    <p class="mt-4 text-ink-700">{{ $order->payment_method->label() }}</p>
                    <x-badge :color="$order->payment_status->value === 'paid' ? 'green' : 'slate'" class="mt-2">{{ $order->payment_status->label() }}</x-badge>
                </div>
                <div class="rounded-3xl bg-primary p-6 text-primary-100">
                    <p>Une question sur votre commande ?</p>
                    <a href="{{ route('pages.contact') }}" class="mt-3 inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-accent-300">Nous contacter <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
