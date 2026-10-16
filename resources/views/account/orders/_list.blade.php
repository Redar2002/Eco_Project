@if ($orders->isEmpty())
    <x-empty-state icon="clipboard" title="Aucune commande pour le moment" description="Vos commandes apparaîtront ici dès votre premier achat.">
        <x-button :href="route('products.index')">Découvrir la boutique</x-button>
    </x-empty-state>
@else
    <ul class="space-y-3">
        @foreach ($orders as $order)
            <li>
                <a href="{{ route('account.orders.show', $order) }}" class="group flex flex-wrap items-center gap-4 border border-ink-100 p-4 transition hover:border-accent hover:shadow-soft sm:p-5">
                    <div class="flex h-11 w-11 items-center justify-center bg-ink-50 text-ink-500"><x-icon name="cube" /></div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-ink-900">{{ $order->number }}</p>
                        <p class="text-sm text-ink-500">{{ $order->created_at->translatedFormat('d F Y') }} · {{ $order->items_count }} {{ Str::plural('article', $order->items_count) }}</p>
                    </div>
                    <x-badge :color="$order->status->color()">{{ $order->status->label() }}</x-badge>
                    <p class="w-28 text-right font-semibold text-ink-900">{{ format_mad($order->total) }}</p>
                    <x-icon name="chevron-right" class="hidden h-5 w-5 text-ink-300 transition group-hover:translate-x-1 group-hover:text-accent-700 sm:block" />
                </a>
            </li>
        @endforeach
    </ul>
@endif
