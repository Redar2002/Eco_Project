@props(['title', 'subtitle' => null, 'eyebrow' => null])
<section class="container-shop pt-6">
    <div class="relative overflow-hidden rounded-[2rem] bg-primary-950 px-6 py-14 text-center text-white sm:px-12 sm:py-20">
        <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-primary-600/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 h-64 w-64 rounded-full bg-accent/20 blur-3xl"></div>
        <div class="relative">
            @if ($eyebrow)<p class="text-xs font-semibold uppercase tracking-[.2em] text-accent-300">{{ $eyebrow }}</p>@endif
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $title }}</h1>
            @if ($subtitle)<p class="mx-auto mt-4 max-w-2xl text-lg text-primary-100/80">{{ $subtitle }}</p>@endif
        </div>
    </div>
</section>
