{{-- Badge coloré. Couleurs : primary, accent, green, red, amber, blue, indigo, slate, dark --}}
@props(['color' => 'slate'])
@php
$colors = [
    'primary' => 'bg-primary text-white',
    'accent' => 'bg-accent text-ink-900',
    'dark' => 'bg-ink-900 text-white',
    'green' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
    'red' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
    'amber' => 'bg-amber-50 text-amber-800 ring-1 ring-inset ring-amber-600/20',
    'blue' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
    'indigo' => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20',
    'slate' => 'bg-ink-100 text-ink-700 ring-1 ring-inset ring-ink-500/10',
];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold '.($colors[$color] ?? $colors['slate'])]) }}>
    {{ $slot }}
</span>
