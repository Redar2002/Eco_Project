<x-account-layout :title="'Commande '.$order->number" :subtitle="'Passée le '.$order->created_at->translatedFormat('d F Y à H:i')">
    <a href="{{ route('account.orders.index') }}" class="-mt-4 mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-ink-500 hover:text-ink-900">
        <x-icon name="arrow-left" class="h-4 w-4" /> Toutes mes commandes
    </a>

    <div class="rounded-3xl border border-ink-100 p-6">
        @include('partials.order-timeline', ['order' => $order])
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="overflow-hidden rounded-3xl border border-ink-100 lg:col-span-2">
            <h2 class="border-b border-ink-100 px-6 py-4 font-sans text-base font-semibold">Articles</h2>
            @include('partials.order-items', ['order' => $order])
        </div>
        <div class="space-y-6">
            <div class="rounded-3xl border border-ink-100 p-6 text-sm">
                <h2 class="font-sans text-base font-semibold">Livraison</h2>
                <p class="mt-3 font-medium text-ink-900">{{ $order->customer_name }}</p>
                <p class="text-ink-600">{{ $order->address }}</p>
                <p class="text-ink-600">{{ $order->city }}</p>
                <p class="mt-2 text-ink-600">{{ $order->phone }}</p>
                @if ($order->notes)<p class="mt-3 rounded-xl bg-ink-50 p-3 text-ink-600">{{ $order->notes }}</p>@endif
            </div>
            <div class="rounded-3xl border border-ink-100 p-6 text-sm">
                <h2 class="font-sans text-base font-semibold">Paiement</h2>
                <p class="mt-3 text-ink-700">{{ $order->payment_method->label() }}</p>
                <x-badge :color="$order->payment_status->value === 'paid' ? 'green' : 'slate'" class="mt-2">{{ $order->payment_status->label() }}</x-badge>
            </div>
        </div>
    </div>
</x-account-layout>
