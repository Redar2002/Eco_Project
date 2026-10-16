{{-- Alerte. Types : success, error, warning, info. « dismissible » ajoute un bouton de fermeture. --}}
@props(['type' => 'info', 'title' => null, 'dismissible' => false])
@php
$styles = [
    'success' => ['bg-green-50 text-green-800 ring-green-600/20', 'check-circle', 'text-green-600'],
    'error' => ['bg-red-50 text-red-800 ring-red-600/20', 'error', 'text-red-600'],
    'warning' => ['bg-amber-50 text-amber-900 ring-amber-600/20', 'warning', 'text-amber-600'],
    'info' => ['bg-blue-50 text-blue-800 ring-blue-600/20', 'info', 'text-blue-600'],
];
[$box, $icon, $iconColor] = $styles[$type] ?? $styles['info'];
@endphp
<div x-data="{ open: true }" x-show="open" x-transition.opacity role="alert"
     {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-2xl px-4 py-3 text-sm ring-1 ring-inset $box"]) }}>
    <x-icon :name="$icon" class="mt-0.5 h-5 w-5 {{ $iconColor }}" />
    <div class="flex-1">
        @if ($title)<p class="font-semibold">{{ $title }}</p>@endif
        <div @class(['mt-0.5' => $title])>{{ $slot }}</div>
    </div>
    @if ($dismissible)
        <button type="button" @click="open = false" class="-m-1 rounded-full p-1 opacity-60 transition hover:opacity-100" aria-label="Fermer">
            <x-icon name="x" class="h-4 w-4" />
        </button>
    @endif
</div>
