<x-admin-layout title="Catégories">
    <x-slot name="actions">
        <x-button :href="route('admin.categories.create')" size="sm" icon="plus">Nouvelle catégorie</x-button>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($categories as $category)
            <div class="flex gap-4 rounded-2xl bg-white p-4 shadow-soft ring-1 ring-ink-900/5">
                <div class="h-24 w-20 shrink-0 overflow-hidden rounded-xl bg-ink-100">
                    @if ($category->image_url)<img src="{{ $category->image_url }}" alt="" class="h-full w-full object-cover">@endif
                </div>
                <div class="flex min-w-0 flex-1 flex-col">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h2 class="truncate font-sans text-base font-semibold">{{ $category->name }}</h2>
                            <p class="text-xs text-ink-500">/{{ $category->slug }} · position {{ $category->position }}</p>
                        </div>
                        @if ($category->is_featured)<x-badge color="accent">Vedette</x-badge>@endif
                    </div>
                    <p class="mt-1 text-sm text-ink-600">{{ $category->products_count }} {{ Str::plural('produit', $category->products_count) }}</p>
                    <div class="mt-auto flex justify-end gap-1">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex rounded-lg p-2 text-ink-500 hover:bg-ink-100 hover:text-ink-900" aria-label="Modifier"><x-icon name="pencil" class="h-4 w-4" /></a>
                        <x-confirm-delete :action="route('admin.categories.destroy', $category)" :name="'delete-category-'.$category->id" icon-only
                                          title="Supprimer cette catégorie ?" message="Une catégorie ne peut être supprimée que si elle ne contient aucun produit." />
                    </div>
                </div>
            </div>
        @empty
            <x-empty-state icon="grid" title="Aucune catégorie" class="bg-white sm:col-span-2 xl:col-span-3" />
        @endforelse
    </div>
</x-admin-layout>
