<x-admin-layout title="Messages">
    <div class="flex gap-2">
        @foreach (['' => 'Tous', 'non-lus' => 'Non lus'] as $value => $label)
            @php $active = (string) request('filtre') === $value; @endphp
            <a href="{{ route('admin.messages.index', array_filter(['filtre' => $value, 'q' => request('q')])) }}"
               @class(['rounded-full px-4 py-2 text-sm font-medium transition', 'bg-ink-900 text-white' => $active, 'bg-white text-ink-600 ring-1 ring-ink-200 hover:ring-ink-400' => ! $active])>
                {{ $label }} @if ($value === 'non-lus' && $unreadCount)<span class="ml-1 opacity-70">{{ $unreadCount }}</span>@endif
            </a>
        @endforeach
    </div>

    <x-admin.card class="mt-6" :padding="false">
        <form method="GET" class="flex flex-wrap gap-3 border-b border-ink-100 p-4">
            @if (request('filtre'))<input type="hidden" name="filtre" value="{{ request('filtre') }}">@endif
            <div class="relative min-w-56 flex-1">
                <label for="q" class="sr-only">Rechercher</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" />
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail ou contenu…" class="form-input pl-10">
            </div>
            <x-button variant="secondary" icon="search">Rechercher</x-button>
        </form>

        @if ($messages->isEmpty())
            <p class="px-5 py-12 text-center text-sm text-ink-500">Aucun message pour le moment.</p>
        @else
            <ul class="divide-y divide-ink-100">
                @foreach ($messages as $msg)
                    <li>
                        <a href="{{ route('admin.messages.show', $msg) }}" class="flex items-start gap-4 px-5 py-4 transition hover:bg-ink-50/60">
                            <span @class(['mt-2 h-2.5 w-2.5 shrink-0 rounded-full', 'bg-accent' => ! $msg->read_at, 'bg-transparent' => $msg->read_at])
                                  title="{{ $msg->read_at ? 'Lu' : 'Non lu' }}"></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <p @class(['text-ink-900', 'font-semibold' => ! $msg->read_at, 'font-medium' => $msg->read_at])>{{ $msg->name }}</p>
                                    <x-badge color="slate">{{ $msg->subject_label }}</x-badge>
                                </div>
                                <p class="mt-0.5 text-xs text-ink-500">{{ $msg->email }}@if ($msg->phone) · {{ $msg->phone }}@endif</p>
                                <p class="mt-1.5 truncate text-sm text-ink-600">{{ $msg->message }}</p>
                            </div>
                            <p class="shrink-0 text-xs text-ink-400">{{ $msg->created_at->diffForHumans() }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.card>

    <div class="mt-6">{{ $messages->links() }}</div>
</x-admin-layout>
