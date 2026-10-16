@props(['title', 'subtitle' => null, 'eyebrow' => null, 'href' => null, 'linkLabel' => 'Tout voir'])
<div {{ $attributes->merge(['class' => 'flex flex-wrap items-end justify-between gap-4']) }}>
    <div>
        @if ($eyebrow)<p class="text-xs font-semibold uppercase tracking-[.2em] text-accent-600">{{ $eyebrow }}</p>@endif
        <h2 class="mt-1 text-2xl font-semibold tracking-tight sm:text-3xl">{{ $title }}</h2>
        @if ($subtitle)<p class="mt-2 max-w-xl text-ink-500">{{ $subtitle }}</p>@endif
    </div>
    @if ($href)
        <a href="{{ $href }}" class="group inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-950">
            {{ $linkLabel }}
            <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
        </a>
    @endif
</div>
