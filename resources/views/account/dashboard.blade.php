<x-account-layout :title="'Bonjour '.Str::before($user->name, ' ').' 👋'" subtitle="Bienvenue dans votre espace client TirrainStyle.">
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-ink-100 p-5">
            <p class="text-sm text-ink-500">Commandes passées</p>
            <p class="mt-2 font-display text-3xl font-semibold text-ink-900">{{ $ordersCount }}</p>
        </div>
        <div class="rounded-2xl border border-ink-100 p-5">
            <p class="text-sm text-ink-500">Total dépensé</p>
            <p class="mt-2 font-display text-3xl font-semibold text-ink-900">{{ format_mad($totalSpent) }}</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="group rounded-2xl bg-primary p-5 text-white transition hover:bg-primary-950">
            <p class="text-sm text-primary-100">Mon profil</p>
            <p class="mt-2 flex items-center gap-2 font-semibold">Gérer mes informations <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" /></p>
        </a>
    </div>

    <div class="mt-10">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Commandes récentes</h2>
            @if ($ordersCount > 0)
                <a href="{{ route('account.orders.index') }}" class="text-sm font-semibold text-primary hover:text-primary-950">Tout voir</a>
            @endif
        </div>
        <div class="mt-4">
            @include('account.orders._list', ['orders' => $recentOrders])
        </div>
    </div>
</x-account-layout>
