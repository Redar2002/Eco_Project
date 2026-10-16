{{--
    Bouton réutilisable (style « Noir & Or » : angles nets, capitales espacées).
    Rendu en <a> si « href » est fourni, sinon en <button>.
    Variantes : primary, accent, secondary, outline, ghost, danger, white. Tailles : sm, md, lg.
--}}
@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'submit', 'icon' => null, 'iconRight' => null])
@php
$base = 'group/btn relative inline-flex items-center justify-center gap-2.5 overflow-hidden font-semibold uppercase tracking-[0.16em] transition duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 active:scale-[.98]';
$variants = [
    'primary' => 'bg-ink-950 text-white hover:bg-accent hover:text-ink-950',
    'accent' => 'bg-gold-gradient bg-[length:200%_100%] bg-left text-ink-950 shadow-gold hover:bg-right',
    'secondary' => 'bg-ink-100 text-ink-900 hover:bg-ink-200',
    'outline' => 'border border-ink-950 bg-transparent text-ink-950 hover:bg-ink-950 hover:text-white',
    'ghost' => 'text-ink-700 hover:bg-ink-100 hover:text-ink-950',
    'danger' => 'bg-rouge text-white hover:bg-rouge-800',
    'white' => 'bg-white text-ink-950 hover:bg-accent',
];
$sizes = [
    'sm' => 'px-4 py-2.5 text-[10px]',
    'md' => 'px-7 py-3.5 text-[11px]',
    'lg' => 'px-9 py-4 text-xs',
];
$classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
$iconSize = $size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-icon :name="$icon" :class="$iconSize" />@endif
        {{ $slot }}
        @if ($iconRight)<x-icon :name="$iconRight" :class="$iconSize.' transition-transform duration-300 group-hover/btn:translate-x-1'" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-icon :name="$icon" :class="$iconSize" />@endif
        {{ $slot }}
        @if ($iconRight)<x-icon :name="$iconRight" :class="$iconSize.' transition-transform duration-300 group-hover/btn:translate-x-1'" />@endif
    </button>
@endif
