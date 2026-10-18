@props(['icon' => 'bag', 'title', 'description' => null])
<div {{ $attributes->merge(['class' => 'flex flex-col items-center rounded-3xl border border-dashed border-ink-200 px-6 py-16 text-center']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary">
        <x-icon :name="$icon" class="h-7 w-7" />
    </div>
    <h3 class="mt-4 text-lg font-semibold">{{ $title }}</h3>
    @if ($description)<p class="mt-1 max-w-sm text-sm text-ink-500">{{ $description }}</p>@endif
    @if ($slot->isNotEmpty())<div class="mt-6">{{ $slot }}</div>@endif
</div>
