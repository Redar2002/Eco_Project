@props(['label', 'value', 'icon', 'color' => 'primary', 'hint' => null, 'href' => null])
@php
$colors = [
    'primary' => 'bg-accent-50 text-accent-700',
    'accent' => 'bg-accent-50 text-accent-600',
    'green' => 'bg-green-50 text-green-600',
    'red' => 'bg-red-50 text-red-600',
];
$tag = $href ? 'a' : 'div';
@endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @endif class=" bg-white p-5 shadow-soft ring-1 ring-ink-900/5 transition {{ $href ? 'hover:-translate-y-0.5 hover:shadow-lift' : '' }}">
    <div class="flex items-start justify-between">
        <p class="text-sm font-medium text-ink-500">{{ $label }}</p>
        <span class="flex h-10 w-10 items-center justify-center {{ $colors[$color] }}"><x-icon :name="$icon" /></span>
    </div>
    <p class="mt-2 font-display text-2xl font-semibold tracking-tight text-ink-900 sm:text-3xl">{{ $value }}</p>
    @if ($hint)<p class="mt-1 text-xs text-ink-500">{{ $hint }}</p>@endif
</{{ $tag }}>
