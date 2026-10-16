{{-- Alerte. Types : success, error, warning, info. « dismissible » ajoute un bouton de fermeture. --}}
@props(['type' => 'info', 'title' => null, 'dismissible' => false])
@php
$styles = [
    'success' => ['border-green-700 bg-green-50/70 text-green-900', 'check-circle', 'text-green-700'],
    'error' => ['border-rouge bg-red-50/70 text-red-900', 'error', 'text-rouge'],
    'warning' => ['border-accent bg-ivory text-ink-900', 'warning', 'text-accent-700'],
    'info' => ['border-ink-950 bg-ink-50 text-ink-800', 'info', 'text-ink-950'],
];
[$box, $icon, $iconColor] = $styles[$type] ?? $styles['info'];
@endphp
<div x-data="{ open: true }" x-show="open" x-transition.opacity role="alert"
     {{ $attributes->merge(['class' => "flex items-start gap-3 border-l-2 px-4 py-3.5 text-sm $box"]) }}>
    <x-icon :name="$icon" class="mt-0.5 h-5 w-5 {{ $iconColor }}" />
    <div class="flex-1">
        @if ($title)<p class="font-semibold">{{ $title }}</p>@endif
        <div @class(['mt-0.5' => $title])>{{ $slot }}</div>
    </div>
    @if ($dismissible)
        <button type="button" @click="open = false" class="-m-1 p-1 opacity-60 transition hover:opacity-100" aria-label="Fermer">
            <x-icon name="x" class="h-4 w-4" />
        </button>
    @endif
</div>
