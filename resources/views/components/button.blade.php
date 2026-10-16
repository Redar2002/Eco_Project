{{--
    Bouton réutilisable. Rendu en <a> si « href » est fourni, sinon en <button>.
    Variantes : primary, accent, secondary, outline, ghost, danger, white. Tailles : sm, md, lg.
--}}
@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'submit', 'icon' => null, 'iconRight' => null])
@php
$base = 'inline-flex items-center justify-center gap-2 rounded-full font-semibold tracking-tight transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 active:scale-[.98]';
$variants = [
    'primary' => 'bg-primary text-white shadow-sm hover:bg-primary-950 hover:shadow-md focus-visible:ring-primary-600',
    'accent' => 'bg-accent text-ink-900 shadow-sm hover:bg-accent-400 hover:shadow-md focus-visible:ring-accent-500',
    'secondary' => 'bg-ink-100 text-ink-900 hover:bg-ink-200 focus-visible:ring-ink-400',
    'outline' => 'border border-ink-300 bg-white text-ink-900 hover:border-ink-900 focus-visible:ring-ink-400',
    'ghost' => 'text-ink-700 hover:bg-ink-100 hover:text-ink-900 focus-visible:ring-ink-300',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-500',
    'white' => 'bg-white text-ink-900 shadow-sm hover:bg-ink-50 focus-visible:ring-white',
];
$sizes = [
    'sm' => 'px-4 py-2 text-xs',
    'md' => 'px-6 py-3 text-sm',
    'lg' => 'px-8 py-4 text-base',
];
$classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
$iconSize = $size === 'sm' ? 'h-4 w-4' : 'h-5 w-5';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-icon :name="$icon" :class="$iconSize" />@endif
        {{ $slot }}
        @if ($iconRight)<x-icon :name="$iconRight" :class="$iconSize" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-icon :name="$icon" :class="$iconSize" />@endif
        {{ $slot }}
        @if ($iconRight)<x-icon :name="$iconRight" :class="$iconSize" />@endif
    </button>
@endif
