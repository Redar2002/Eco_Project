{{-- Badge. Couleurs : promo (rouge), new (noir/or), gold, dark, green, red, amber, blue, indigo, slate --}}
@props(['color' => 'slate'])
@php
$colors = [
    'promo' => 'bg-rouge text-white',
    'new' => 'bg-ink-950 text-accent-300',
    'gold' => 'bg-accent text-ink-950',
    'accent' => 'bg-accent text-ink-950',
    'dark' => 'bg-ink-950 text-white',
    'primary' => 'bg-ink-950 text-white',
    'green' => 'bg-green-50 text-green-800 ring-1 ring-inset ring-green-700/20',
    'red' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-700/20',
    'amber' => 'bg-accent-50 text-accent-800 ring-1 ring-inset ring-accent-600/30',
    'blue' => 'bg-blue-50 text-blue-800 ring-1 ring-inset ring-blue-700/20',
    'indigo' => 'bg-ink-950 text-accent-200',
    'slate' => 'bg-ink-100 text-ink-700 ring-1 ring-inset ring-ink-500/10',
];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] '.($colors[$color] ?? $colors['slate'])]) }}>
    {{ $slot }}
</span>
