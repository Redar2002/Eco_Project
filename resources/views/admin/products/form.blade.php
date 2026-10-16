@php $editing = $product->exists; @endphp
<x-admin-layout :title="$editing ? 'Modifier : '.$product->name : 'Nouveau produit'">
    <x-slot name="actions">
        <x-button :href="route('admin.products.index')" variant="ghost" size="sm" icon="arrow-left">Retour</x-button>
    </x-slot>

    @if ($errors->any())
        <x-alert type="error" title="Le formulaire contient des erreurs." class="mb-6">Merci de vérifier les champs indiqués ci-dessous.</x-alert>
    @endif

    <form method="POST" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="grid gap-6 xl:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="space-y-6 xl:col-span-2">
            <x-admin.card title="Informations générales">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-input name="name" label="Nom du produit" :value="$product->name" required class="sm:col-span-2" />
                    <x-select name="category_id" label="Catégorie" :options="$categories" :value="$product->category_id" placeholder="Choisir…" required />
                    <x-input name="slug" label="Slug (URL)" :value="$product->slug" hint="Laisser vide pour le générer automatiquement." />
                    <x-textarea name="description" label="Description" :value="$product->description" rows="7" required class="sm:col-span-2" />
                </div>
            </x-admin.card>

            {{-- Images : aperçu des nouveaux fichiers avec Alpine.js --}}
            <x-admin.card title="Images">
                @if ($editing && $product->images->isNotEmpty())
                    <div class="mb-5 grid grid-cols-3 gap-3 sm:grid-cols-5">
                        @foreach ($product->images as $image)
                            <div class="group relative aspect-[4/5] overflow-hidden bg-ink-100">
                                <img src="{{ $image->url }}" alt="" class="h-full w-full object-cover">
                                @if ($loop->first)<span class="absolute left-2 top-2 bg-ink-900/80 px-2 py-0.5 text-[10px] font-semibold text-white">Principale</span>@endif
                                <button type="submit" form="delete-image-{{ $image->id }}" class="absolute right-2 top-2 bg-white/90 p-1.5 text-red-600 opacity-100 shadow transition hover:bg-red-600 hover:text-white lg:opacity-0 lg:group-hover:opacity-100" aria-label="Supprimer l'image">
                                    <x-icon name="trash" class="h-4 w-4" />
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div x-data="{ previews: [] }">
                    <label for="images" class="flex cursor-pointer flex-col items-center justify-center border-2 border-dashed border-ink-200 px-6 py-10 text-center transition hover:border-accent hover:bg-ivory">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-accent-50 text-accent-700"><x-icon name="photo" class="h-6 w-6" /></span>
                        <span class="mt-3 text-sm font-semibold text-ink-900">Cliquez pour ajouter des images</span>
                        <span class="mt-1 text-xs text-ink-500">JPG, PNG ou WebP · 3 Mo max · 8 images max</span>
                    </label>
                    <input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only"
                           @change="previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
                    <div x-show="previews.length" x-cloak class="mt-4 grid grid-cols-3 gap-3 sm:grid-cols-5">
                        <template x-for="src in previews" :key="src">
                            <img :src="src" alt="" class="aspect-[4/5] w-full object-cover ring-2 ring-accent/50">
                        </template>
                    </div>
                    @error('images')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    @foreach ($errors->get('images.*') as $messages)
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $messages[0] }}</p>
                    @endforeach
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Prix & stock">
                <div class="space-y-5">
                    <x-input name="price" type="number" step="0.01" min="0" label="Prix (MAD)" :value="$product->price" required />
                    <x-input name="sale_price" type="number" step="0.01" min="0" label="Prix promo (MAD)" :value="$product->sale_price" hint="Laisser vide s'il n'y a pas de promotion." />
                    <x-input name="stock" type="number" min="0" label="Stock" :value="$product->stock" required />
                </div>
            </x-admin.card>

            <x-admin.card title="Visibilité">
                <div class="space-y-4">
                    <label class="flex items-start justify-between gap-4">
                        <span>
                            <span class="block text-sm font-medium text-ink-900">Actif</span>
                            <span class="text-xs text-ink-500">Visible dans la boutique</span>
                        </span>
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="mt-1 h-5 w-5 rounded border-ink-300 text-primary focus:ring-accent">
                    </label>
                    <label class="flex items-start justify-between gap-4">
                        <span>
                            <span class="block text-sm font-medium text-ink-900">Mis en avant</span>
                            <span class="text-xs text-ink-500">Affiché dans « Les plus populaires »</span>
                        </span>
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="mt-1 h-5 w-5 rounded border-ink-300 text-primary focus:ring-accent">
                    </label>
                </div>
            </x-admin.card>

            <div class="flex flex-col gap-3">
                <x-button size="lg" icon="check">{{ $editing ? 'Enregistrer les modifications' : 'Créer le produit' }}</x-button>
                @if ($editing)
                    <x-confirm-delete :action="route('admin.products.destroy', $product)" name="delete-product" class="justify-center"
                                      title="Supprimer ce produit ?" message="Le produit et ses images seront définitivement supprimés." label="Supprimer le produit" />
                @endif
            </div>
        </div>
    </form>

    {{-- Formulaires de suppression d'images (hors du formulaire principal) --}}
    @if ($editing)
        @foreach ($product->images as $image)
            <form id="delete-image-{{ $image->id }}" method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endif
</x-admin-layout>
