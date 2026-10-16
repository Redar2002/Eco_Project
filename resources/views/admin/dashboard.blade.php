<x-admin-layout title="Tableau de bord">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat label="Chiffre d'affaires" :value="format_mad($stats['revenue'])" icon="cash" :hint="'Ce mois-ci : '.format_mad($stats['revenueMonth'])" />
        <x-admin.stat label="Commandes" :value="$stats['orders']" icon="clipboard" color="accent" :hint="$stats['pending'].' en attente de traitement'" :href="route('admin.orders.index', ['status' => 'pending'])" />
        <x-admin.stat label="Clients" :value="$stats['customers']" icon="users" color="green" :href="route('admin.customers.index')" />
        <x-admin.stat label="Ruptures de stock" :value="$stats['outOfStock']" icon="warning" color="red" hint="Produits à réapprovisionner" :href="route('admin.products.index', ['status' => 'out'])" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        {{-- Graphique des ventes (barres CSS) --}}
        <x-admin.card title="Ventes des 14 derniers jours" class="xl:col-span-2">
            <div class="flex h-64 items-end gap-1.5 pt-10 sm:gap-3" role="img" aria-label="Chiffre d'affaires quotidien">
                @foreach ($chart as $day)
                    <div class="group relative flex h-full flex-1 flex-col items-center justify-end">
                        <div class="pointer-events-none absolute -top-2 z-10 hidden -translate-y-full whitespace-nowrap bg-ink-900 px-2 py-1 text-xs text-white group-hover:block">
                            {{ $day['label'] }} : {{ format_mad($day['value']) }}
                        </div>
                        <div class="w-full  bg-primary/85 transition group-hover:bg-accent" style="height: {{ max(2, $day['value'] / $chartMax * 100) }}%"></div>
                        <span class="mt-2 text-[10px] text-ink-400 sm:text-xs">{{ $day['short'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-admin.card>

        {{-- Meilleures ventes --}}
        <x-admin.card title="Meilleures ventes" :padding="false">
            <ul class="divide-y divide-ink-100">
                @forelse ($topProducts as $i => $top)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-ink-100 text-xs font-bold text-ink-600">{{ $i + 1 }}</span>
                        <p class="min-w-0 flex-1 truncate text-sm font-medium text-ink-900">{{ $top->product_name }}</p>
                        <div class="text-right">
                            <p class="text-sm font-semibold">{{ $top->sold }} vendus</p>
                            <p class="text-xs text-ink-500">{{ format_mad($top->revenue) }}</p>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-ink-500">Aucune vente pour le moment.</li>
                @endforelse
            </ul>
        </x-admin.card>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        {{-- Dernières commandes --}}
        <x-admin.card title="Dernières commandes" :padding="false" class="xl:col-span-2">
            <x-slot name="action"><a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-ink-950 underline decoration-accent decoration-2 underline-offset-4 hover:text-accent-700">Tout voir</a></x-slot>
            @include('admin.orders._table', ['orders' => $latestOrders, 'compact' => true])
        </x-admin.card>

        {{-- Stock faible --}}
        <x-admin.card title="Stock faible / rupture" :padding="false">
            <x-slot name="action"><a href="{{ route('admin.products.index', ['status' => 'low']) }}" class="text-sm font-semibold text-ink-950 underline decoration-accent decoration-2 underline-offset-4 hover:text-accent-700">Gérer</a></x-slot>
            <ul class="divide-y divide-ink-100">
                @forelse ($outOfStockProducts as $product)
                    <li>
                        <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-3 px-5 py-3 transition hover:bg-ink-50">
                            <img src="{{ $product->image_url }}" alt="" class="h-10 w-9 bg-ink-100 object-cover">
                            <p class="min-w-0 flex-1 truncate text-sm font-medium text-ink-900">{{ $product->name }}</p>
                            <x-badge :color="$product->stock === 0 ? 'red' : 'amber'">{{ $product->stock === 0 ? 'Rupture' : $product->stock.' restant(s)' }}</x-badge>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-ink-500">Tous les stocks sont au vert 👌</li>
                @endforelse
            </ul>
        </x-admin.card>
    </div>
</x-admin-layout>
