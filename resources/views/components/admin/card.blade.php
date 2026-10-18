@props(['title' => null, 'padding' => true])
<section {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl bg-white shadow-soft ring-1 ring-ink-900/5']) }}>
    @if ($title || isset($action))
        <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
            <h2 class="font-sans text-base font-semibold text-ink-900">{{ $title }}</h2>
            {{ $action ?? '' }}
        </div>
    @endif
    <div @class(['p-5' => $padding])>{{ $slot }}</div>
</section>
