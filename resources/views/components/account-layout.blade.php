{{-- Gabarit de l'espace client : navigation à gauche, contenu à droite --}}
@props(['title', 'subtitle' => null])
<x-app-layout :title="$title">
    <div class="container-shop py-10">
        <div class="grid gap-8 lg:grid-cols-[15rem_1fr] lg:gap-12">
            <aside>
                <div class="mb-6 hidden items-center gap-3 lg:flex">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary font-semibold text-white">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-ink-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-ink-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                @include('account._nav')
            </aside>
            <div class="min-w-0">
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</h1>
                @if ($subtitle)<p class="mt-1 text-ink-500">{{ $subtitle }}</p>@endif
                <div class="mt-8">{{ $slot }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
