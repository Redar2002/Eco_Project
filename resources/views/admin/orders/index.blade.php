<x-admin-layout title="Commandes">
    {{-- Onglets par statut --}}
    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none">
        @php $current = request('status'); @endphp
        <a href="{{ route('admin.orders.index', request()->except('status', 'page')) }}" @class(['shrink-0 rounded-full px-4 py-2 text-sm font-medium transition', 'bg-ink-900 text-white' => ! $current, 'bg-white text-ink-600 ring-1 ring-ink-200 hover:ring-ink-400' => $current])>
            Toutes <span class="ml-1 opacity-60">{{ $statusCounts->sum() }}</span>
        </a>
        @foreach (\App\Enums\OrderStatus::cases() as $status)
            <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => $status->value])) }}" @class(['shrink-0 rounded-full px-4 py-2 text-sm font-medium transition', 'bg-ink-900 text-white' => $current === $status->value, 'bg-white text-ink-600 ring-1 ring-ink-200 hover:ring-ink-400' => $current !== $status->value])>
                {{ $status->label() }} <span class="ml-1 opacity-60">{{ $statusCounts[$status->value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <x-admin.card class="mt-6" :padding="false">
        <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-ink-100 p-4">
            @if ($current)<input type="hidden" name="status" value="{{ $current }}">@endif
            <div class="relative min-w-56 flex-1">
                <label for="q" class="sr-only">Rechercher</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="N° de commande, client, téléphone…" class="form-input pl-10">
            </div>
            <div>
                <label for="from" class="form-label text-xs">Du</label>
                <input id="from" type="date" name="from" value="{{ request('from') }}" class="form-input">
            </div>
            <div>
                <label for="to" class="form-label text-xs">Au</label>
                <input id="to" type="date" name="to" value="{{ request('to') }}" class="form-input">
            </div>
            <x-button variant="secondary" icon="filter">Filtrer</x-button>
            @if (request()->hasAny(['q', 'from', 'to']))
                <x-button :href="route('admin.orders.index', array_filter(['status' => $current]))" variant="ghost">Effacer</x-button>
            @endif
        </form>
        @include('admin.orders._table', ['orders' => $orders])
    </x-admin.card>

    <div class="mt-6">{{ $orders->links() }}</div>
</x-admin-layout>
