{{-- Bouton de suppression avec fenêtre modale de confirmation --}}
@props(['action', 'name', 'title' => 'Confirmer la suppression', 'message' => 'Cette action est irréversible.', 'label' => 'Supprimer', 'iconOnly' => false])
<button type="button" x-data @click.prevent="$dispatch('open-modal', '{{ $name }}')"
        {{ $attributes->merge(['class' => $iconOnly ? 'inline-flex p-2 text-ink-500 transition hover:bg-red-50 hover:text-red-600' : 'inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50']) }}
        aria-label="{{ $label }}">
    <x-icon name="trash" class="h-4 w-4" /> @unless ($iconOnly){{ $label }}@endunless
</button>

@push('modals')
    <x-modal :name="$name" max-width="md" focusable>
        <form method="POST" action="{{ $action }}" class="p-6 text-left">
            @csrf
            @method('DELETE')
            <div class="flex items-start gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600"><x-icon name="warning" /></span>
                <div>
                    <h2 class="text-lg font-semibold text-ink-900">{{ $title }}</h2>
                    <p class="mt-1 text-sm text-ink-500">{{ $message }}</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <x-danger-button>{{ $label }}</x-danger-button>
            </div>
        </form>
    </x-modal>
@endpush
