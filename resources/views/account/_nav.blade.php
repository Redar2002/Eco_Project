{{-- Navigation latérale de l'espace client --}}
@php
$links = [
    ['dashboard', 'home', 'Tableau de bord'],
    ['account.orders.index', 'clipboard', 'Mes commandes'],
    ['profile.edit', 'user', 'Mon profil'],
];
@endphp
<nav class="flex gap-2 overflow-x-auto scrollbar-none lg:flex-col" aria-label="Espace client">
    @foreach ($links as [$route, $icon, $label])
        @php $active = request()->routeIs($route) || ($route === 'account.orders.index' && request()->routeIs('account.orders.*')); @endphp
        <a href="{{ route($route) }}" @class([
            'flex shrink-0 items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition',
            'bg-primary text-white shadow-sm' => $active,
            'text-ink-600 hover:bg-ink-100 hover:text-ink-900' => ! $active,
        ])>
            <x-icon :name="$icon" class="h-5 w-5" /> {{ $label }}
        </a>
    @endforeach
    <form method="POST" action="{{ route('logout') }}" class="shrink-0 lg:mt-4 lg:border-t lg:border-ink-100 lg:pt-4">
        @csrf
        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-ink-600 transition hover:bg-red-50 hover:text-red-600">
            <x-icon name="logout" class="h-5 w-5" /> Déconnexion
        </button>
    </form>
</nav>
