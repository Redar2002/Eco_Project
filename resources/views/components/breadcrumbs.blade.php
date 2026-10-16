{{-- Fil d'Ariane. « items » = [libellé => url|null] --}}
@props(['items' => []])
<nav aria-label="Fil d'Ariane" {{ $attributes->merge(['class' => 'text-sm']) }}>
    <ol class="flex flex-wrap items-center gap-1.5 text-ink-500">
        <li><a href="{{ route('home') }}" class="hover:text-ink-900">Accueil</a></li>
        @foreach ($items as $label => $url)
            <li class="flex items-center gap-1.5">
                <x-icon name="chevron-right" class="h-3.5 w-3.5 text-ink-300" />
                @if ($url)
                    <a href="{{ $url }}" class="hover:text-ink-900">{{ $label }}</a>
                @else
                    <span class="font-medium text-ink-900" aria-current="page">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
