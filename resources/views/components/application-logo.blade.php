{{-- Logo typographique TirrainStyle --}}
@props(['light' => false])
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 font-display text-xl font-bold tracking-tight']) }}>
    <span @class(['flex h-8 w-8 items-center justify-center rounded-xl text-sm', 'bg-white text-primary' => $light, 'bg-primary text-white' => ! $light])>T</span>
    <span @class(['text-white' => $light, 'text-ink-900' => ! $light])>Tirrain<span class="text-accent">Style</span></span>
</span>
