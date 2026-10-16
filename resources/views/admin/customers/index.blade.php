<x-admin-layout title="Clients">
    <x-admin.card :padding="false">
        <form method="GET" class="flex flex-wrap gap-3 border-b border-ink-100 p-4">
            <div class="relative min-w-56 flex-1">
                <label for="q" class="sr-only">Rechercher</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail ou téléphone…" class="form-input pl-10">
            </div>
            <x-button variant="secondary" icon="search">Rechercher</x-button>
        </form>

        @if ($customers->isEmpty())
            <p class="px-5 py-12 text-center text-sm text-ink-500">Aucun client trouvé.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-ink-50/70 text-xs uppercase tracking-wider text-ink-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Client</th>
                            <th class="hidden px-5 py-3 font-semibold md:table-cell">Téléphone</th>
                            <th class="px-5 py-3 font-semibold">Commandes</th>
                            <th class="px-5 py-3 text-right font-semibold">Total dépensé</th>
                            <th class="hidden px-5 py-3 font-semibold lg:table-cell">Inscrit le</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @foreach ($customers as $customer)
                            <tr class="transition hover:bg-ink-50/60">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-950 text-sm font-semibold text-accent">{{ mb_strtoupper(mb_substr($customer->name, 0, 1)) }}</span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-ink-900">{{ $customer->name }}</p>
                                            <p class="truncate text-xs text-ink-500">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden px-5 py-3 text-ink-600 md:table-cell">{{ $customer->phone ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    @if ($customer->orders_count)
                                        <a href="{{ route('admin.orders.index', ['q' => $customer->name]) }}" class="font-medium text-primary hover:underline">{{ $customer->orders_count }}</a>
                                    @else
                                        <span class="text-ink-400">0</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right font-semibold text-ink-900">{{ format_mad($customer->total_spent ?? 0) }}</td>
                                <td class="hidden whitespace-nowrap px-5 py-3 text-ink-600 lg:table-cell">{{ $customer->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.card>

    <div class="mt-6">{{ $customers->links() }}</div>
</x-admin-layout>
