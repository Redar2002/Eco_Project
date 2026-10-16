@props(['title', 'subtitle' => null, 'eyebrow' => null, 'href' => null, 'linkLabel' => 'Tout voir', 'center' => false])
<div {{ $attributes->merge(['class' => $center ? 'text-center' : 'flex flex-wrap items-end justify-between gap-6']) }}>
    <div @class(['mx-auto max-w-2xl' => $center])>
        @if ($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <h2 class="mt-3 text-3xl tracking-tight sm:text-4xl">{{ $title }}</h2>
        @if ($subtitle)<p class="mt-3 text-ink-500">{{ $subtitle }}</p>@endif
    </div>
    @if ($href)
        <a href="{{ $href }}" @class(['group inline-flex items-center gap-2 border-b border-ink-950 pb-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink-950 transition hover:border-accent hover:text-accent-700', 'mt-6' => $center])>
            {{ $linkLabel }}
            <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
        </a>
    @endif
</div>
