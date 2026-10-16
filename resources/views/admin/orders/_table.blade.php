{{-- Tableau des commandes (dashboard + liste) --}}
@if ($orders->isEmpty())
    <p class="px-5 py-10 text-center text-sm text-ink-500">Aucune commande trouvée.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-ink-50/70 text-xs uppercase tracking-wider text-ink-500">
                <tr>
                    <th class="px-5 py-3 font-semibold">Commande</th>
                    <th class="px-5 py-3 font-semibold">Client</th>
                    @empty($compact)<th class="hidden px-5 py-3 font-semibold md:table-cell">Ville</th>@endempty
                    <th class="px-5 py-3 font-semibold">Statut</th>
                    <th class="px-5 py-3 text-right font-semibold">Total</th>
                    @empty($compact)<th class="px-5 py-3"><span class="sr-only">Actions</span></th>@endempty
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach ($orders as $order)
                    <tr class="transition hover:bg-ink-50/60">
                        <td class="whitespace-nowrap px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-ink-900 hover:text-primary">{{ $order->number }}</a>
                            <p class="text-xs text-ink-500">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-ink-900">{{ $order->customer_name }}</p>
                            <p class="text-xs text-ink-500">{{ $order->phone }}</p>
                        </td>
                        @empty($compact)<td class="hidden px-5 py-3 text-ink-600 md:table-cell">{{ $order->city }}</td>@endempty
                        <td class="px-5 py-3"><x-badge :color="$order->status->color()">{{ $order->status->label() }}</x-badge></td>
                        <td class="whitespace-nowrap px-5 py-3 text-right font-semibold text-ink-900">{{ format_mad($order->total) }}</td>
                        @empty($compact)
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex rounded-lg p-2 text-ink-500 hover:bg-ink-100 hover:text-ink-900" aria-label="Voir la commande"><x-icon name="eye" class="h-4 w-4" /></a>
                            </td>
                        @endempty
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
