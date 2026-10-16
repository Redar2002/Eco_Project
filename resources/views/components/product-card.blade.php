{{--
    Carte produit : seconde photo au survol, badges, prix et ajout rapide au panier.
    Charger la relation « images » pour profiter de l'effet de seconde photo.
--}}
@props(['product'])
@php
    $hoverImage = $product->relationLoaded('images') ? $product->images->get(1)?->url : null;
@endphp
<article {{ $attributes->merge(['class' => 'group relative flex flex-col']) }}>
    <div class="relative aspect-[4/5] overflow-hidden bg-ivory">
        <a href="{{ route('products.show', $product) }}" class="block h-full" tabindex="-1" aria-hidden="true">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
                 @class(['h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]', 'group-hover:opacity-0' => $hoverImage])>
            @if ($hoverImage)
                <img src="{{ $hoverImage }}" alt="" loading="lazy"
                     class="absolute inset-0 h-full w-full scale-[1.04] object-cover opacity-0 transition duration-700 ease-out group-hover:scale-100 group-hover:opacity-100">
            @endif
        </a>

        {{-- Liseré doré au survol --}}
        <div class="pointer-events-none absolute inset-3 border border-accent/0 transition duration-500 group-hover:border-accent/70"></div>

        {{-- Badges --}}
        <div class="pointer-events-none absolute left-0 top-4 flex flex-col items-start gap-1.5">
            @if ($product->is_on_sale)
                <x-badge color="promo">-{{ $product->discount_percent }}%</x-badge>
            @endif
            @if ($product->is_new)
                <x-badge color="new">Nouveau</x-badge>
            @endif
        </div>

        @unless ($product->in_stock)
            <div class="absolute inset-x-0 bottom-0 bg-ink-950/85 py-3 text-center text-[10px] font-semibold uppercase tracking-[0.2em] text-white backdrop-blur">
                Épuisé
            </div>
        @else
            {{-- Ajout rapide : glisse depuis le bas au survol (toujours visible sur mobile) --}}
            <form action="{{ route('cart.add', $product) }}" method="POST" x-data="addToCart({{ min($product->stock, config('shop.max_quantity_per_item')) }})"
                  @submit.prevent="submit" class="absolute inset-x-0 bottom-0 transition duration-500 lg:translate-y-full lg:group-hover:translate-y-0 lg:focus-within:translate-y-0">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" :disabled="loading"
                        class="flex w-full items-center justify-center gap-2 bg-ink-950/90 py-3.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-white backdrop-blur transition hover:bg-accent hover:text-ink-950 disabled:opacity-60">
                    <x-icon name="bag" class="h-4 w-4" x-show="!loading" />
                    <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3"/></svg>
                    Ajout rapide
                </button>
            </form>
        @endunless
    </div>

    <div class="mt-4 flex flex-1 flex-col text-center">
        @if ($product->relationLoaded('category') && $product->category)
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-accent-600">{{ $product->category->name }}</p>
        @endif
        <h3 class="mt-1.5 font-display text-base text-ink-950">
            <a href="{{ route('products.show', $product) }}" class="transition hover:text-accent-700">{{ $product->name }}</a>
        </h3>
        <x-price :product="$product" class="mt-1.5 justify-center" />
    </div>
</article>
