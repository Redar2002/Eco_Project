{{-- Logo TirrainStyle : monogramme doré + nom en capitales espacées --}}
@props(['light' => false])
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <span @class([
        'flex h-9 w-9 items-center justify-center border font-display text-lg italic',
        'border-accent text-accent' => true,
        'bg-ink-950' => ! $light,
    ])>T</span>
    <span @class(['font-display text-xl tracking-[0.18em] sm:text-[1.35rem]', 'text-white' => $light, 'text-ink-950' => ! $light])>
        TIRRAIN<span class="text-accent">STYLE</span>
    </span>
</span>
