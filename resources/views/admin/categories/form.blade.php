@php $editing = $category->exists; @endphp
<x-admin-layout :title="$editing ? 'Modifier : '.$category->name : 'Nouvelle catégorie'">
    <x-slot name="actions">
        <x-button :href="route('admin.categories.index')" variant="ghost" size="sm" icon="arrow-left">Retour</x-button>
    </x-slot>

    <form method="POST" action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-admin.card>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-input name="name" label="Nom" :value="$category->name" required />
                <x-input name="slug" label="Slug (URL)" :value="$category->slug" hint="Généré automatiquement si vide." />
                <x-textarea name="description" label="Description" :value="$category->description" rows="3" class="sm:col-span-2" />
                <x-input name="position" type="number" min="0" label="Position (ordre d'affichage)" :value="$category->position" />
                <label class="flex items-center gap-3 self-end pb-3 text-sm font-medium text-ink-700">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $category->is_featured)) class="h-5 w-5 rounded border-ink-300 text-primary focus:ring-primary-600">
                    Mettre en vedette sur l'accueil
                </label>

                <div class="sm:col-span-2" x-data="{ preview: @js($category->image_url) }">
                    <span class="form-label">Image</span>
                    <div class="flex items-center gap-4">
                        <div class="h-28 w-24 shrink-0 overflow-hidden rounded-xl bg-ink-100">
                            <template x-if="preview"><img :src="preview" alt="" class="h-full w-full object-cover"></template>
                        </div>
                        <label for="image" class="cursor-pointer rounded-full border border-ink-300 px-5 py-2.5 text-sm font-semibold text-ink-900 transition hover:border-ink-900">Choisir une image</label>
                        <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only"
                               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview">
                    </div>
                    @error('image')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3 border-t border-ink-100 pt-5">
                <x-button :href="route('admin.categories.index')" variant="ghost">Annuler</x-button>
                <x-button icon="check">{{ $editing ? 'Enregistrer' : 'Créer la catégorie' }}</x-button>
            </div>
        </x-admin.card>
    </form>
</x-admin-layout>
