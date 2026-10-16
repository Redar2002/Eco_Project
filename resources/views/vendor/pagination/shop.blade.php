{{-- Pagination personnalisée de la boutique --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between gap-4">
        <p class="hidden text-sm text-ink-500 sm:block">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} sur {{ $paginator->total() }}
        </p>

        <ul class="flex flex-1 items-center justify-center gap-1 sm:flex-none">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="flex h-10 w-10 items-center justify-center text-ink-300" aria-disabled="true"><x-icon name="chevron-left" class="h-4 w-4" /></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex h-10 w-10 items-center justify-center text-ink-700 transition hover:bg-ink-100" aria-label="Page précédente"><x-icon name="chevron-left" class="h-4 w-4" /></a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="flex h-10 w-10 items-center justify-center text-sm text-ink-400">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="flex h-10 w-10 items-center justify-center bg-primary text-sm font-semibold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="flex h-10 w-10 items-center justify-center text-sm font-medium text-ink-700 transition hover:bg-ink-100" aria-label="Page {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex h-10 w-10 items-center justify-center text-ink-700 transition hover:bg-ink-100" aria-label="Page suivante"><x-icon name="chevron-right" class="h-4 w-4" /></a>
                @else
                    <span class="flex h-10 w-10 items-center justify-center text-ink-300" aria-disabled="true"><x-icon name="chevron-right" class="h-4 w-4" /></span>
                @endif
            </li>
        </ul>
    </nav>
@endif
