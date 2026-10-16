@props(['title', 'subtitle' => null, 'eyebrow' => null])
<section class="relative overflow-hidden bg-ink-950 text-white">
    <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-accent/15 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[.07]" style="background-image: radial-gradient(#C9A24B 1px, transparent 1px); background-size: 22px 22px;"></div>
    <div class="container-shop relative py-20 text-center sm:py-24">
        @if ($eyebrow)<p class="eyebrow justify-center text-accent">{{ $eyebrow }}</p>@endif
        <h1 class="mt-5 text-4xl tracking-tight text-white sm:text-6xl">{{ $title }}</h1>
        <div class="gold-rule mx-auto mt-6"></div>
        @if ($subtitle)<p class="mx-auto mt-6 max-w-2xl text-lg text-ink-300">{{ $subtitle }}</p>@endif
    </div>
</section>
