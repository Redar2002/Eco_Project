<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex">
        <title>{{ isset($title) ? $title.' · ' : '' }}Back-office TirrainStyle</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $nav = [
            ['admin.dashboard', 'admin.dashboard', 'chart', 'Tableau de bord'],
            ['admin.orders.index', 'admin.orders.*', 'clipboard', 'Commandes'],
            ['admin.products.index', 'admin.products.*', 'cube', 'Produits'],
            ['admin.categories.index', 'admin.categories.*', 'grid', 'Catégories'],
            ['admin.customers.index', 'admin.customers.*', 'users', 'Clients'],
        ];
        $pendingCount = \App\Models\Order::where('status', \App\Enums\OrderStatus::Pending)->count();
    @endphp
    <body class="bg-ink-50" x-data="{ sidebar: false }">
        {{-- Barre latérale --}}
        <div x-show="sidebar" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-ink-900/50 lg:hidden" @click="sidebar = false"></div>
        <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-ink-950 text-ink-300 transition-transform duration-300 lg:translate-x-0">
            <div class="flex h-16 items-center justify-between px-6">
                <a href="{{ route('admin.dashboard') }}"><x-application-logo light class="!text-lg" /></a>
                <button type="button" @click="sidebar = false" class="rounded-lg p-1 hover:bg-white/10 lg:hidden" aria-label="Fermer"><x-icon name="x" /></button>
            </div>
            <p class="px-6 pb-2 pt-4 text-[11px] font-semibold uppercase tracking-wider text-ink-500">Gestion</p>
            <nav class="flex-1 space-y-1 px-3" aria-label="Back-office">
                @foreach ($nav as [$route, $pattern, $icon, $label])
                    @php $active = request()->routeIs($pattern); @endphp
                    <a href="{{ route($route) }}" @class([
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                        'bg-white/10 text-white' => $active,
                        'hover:bg-white/5 hover:text-white' => ! $active,
                    ])>
                        <x-icon :name="$icon" @class(['h-5 w-5', 'text-accent' => $active]) />
                        <span class="flex-1">{{ $label }}</span>
                        @if ($route === 'admin.orders.index' && $pendingCount > 0)
                            <span class="rounded-full bg-accent px-2 py-0.5 text-[11px] font-bold text-ink-900">{{ $pendingCount }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="border-t border-white/10 p-3">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition hover:bg-white/5 hover:text-white">
                    <x-icon name="store" /> Voir la boutique
                </a>
                <div class="mt-2 flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-xs font-semibold text-white">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-ink-500">Administrateur</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-1.5 hover:bg-white/10 hover:text-white" aria-label="Se déconnecter" title="Se déconnecter"><x-icon name="logout" class="h-4 w-4" /></button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="lg:pl-64">
            {{-- Barre supérieure --}}
            <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-ink-200/70 bg-white/80 px-4 backdrop-blur sm:px-8">
                <button type="button" @click="sidebar = true" class="-ml-1 rounded-lg p-2 text-ink-600 hover:bg-ink-100 lg:hidden" aria-label="Menu"><x-icon name="menu" /></button>
                <h1 class="truncate text-lg font-semibold">{{ $title ?? 'Back-office' }}</h1>
                <div class="ml-auto flex items-center gap-2">{{ $actions ?? '' }}</div>
            </header>

            <main class="px-4 py-8 sm:px-8">
                {{-- Messages flash --}}
                <div class="space-y-3 empty:hidden">
                    @if (session('success'))<x-alert type="success" dismissible class="mb-6">{{ session('success') }}</x-alert>@endif
                    @if (session('error'))<x-alert type="error" dismissible class="mb-6">{{ session('error') }}</x-alert>@endif
                </div>

                {{ $slot }}
            </main>
        </div>

        {{-- Fenêtres modales empilées par les composants (ex. confirmation de suppression) --}}
        @stack('modals')
    </body>
</html>
