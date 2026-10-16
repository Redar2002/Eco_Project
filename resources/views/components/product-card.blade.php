{{-- Carte produit : image avec zoom au survol, badges, prix et ajout rapide au panier. --}}
@props(['product'])
<article {{ $attributes->merge(['class' => 'group relative flex flex-col']) }}>
    <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-ink-100 transition duration-300 group-hover:shadow-lift">
        <a href="{{ route('products.show', $product) }}" class="block h-full" tabindex="-1">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-105">
        </a>

        {{-- Badges --}}
        <div class="pointer-events-none absolute left-3 top-3 flex flex-col items-start gap-1.5">
            @if ($product->is_on_sale)
                <x-badge color="accent">-{{ $product->discount_percent }}% Promo</x-badge>
            @endif
            @if ($product->is_new)
                <x-badge color="dark">Nouveau</x-badge>
            @endif
        </div>

        @unless ($product->in_stock)
            <div class="absolute inset-x-3 bottom-3 rounded-full bg-white/90 py-2 text-center text-xs font-semibold text-ink-600 backdrop-blur">
                Rupture de stock
            </div>
        @else
            {{-- Ajout rapide : visible au survol sur desktop, toujours visible sur mobile --}}
            <form action="{{ route('cart.add', $product) }}" method="POST" x-data="addToCart({{ min($product->stock, config('shop.max_quantity_per_item')) }})"
                  @submit.prevent="submit" class="absolute inset-x-3 bottom-3 transition duration-300 lg:translate-y-3 lg:opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100 lg:focus-within:translate-y-0 lg:focus-within:opacity-100">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" :disabled="loading"
                        class="flex w-full items-center justify-center gap-2 rounded-full bg-white/95 py-2.5 text-xs font-semibold text-ink-900 shadow-soft backdrop-blur transition hover:bg-primary hover:text-white disabled:opacity-60">
                    <x-icon name="bag" class="h-4 w-4" x-show="!loading" />
                    <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3"/></svg>
                    Ajouter au panier
                </button>
            </form>
        @endunless
    </div>

    <div class="mt-4 flex flex-1 flex-col">
        @if ($product->relationLoaded('category') && $product->category)
            <p class="text-xs font-medium uppercase tracking-wider text-ink-400">{{ $product->category->name }}</p>
        @endif
        <h3 class="mt-1 font-sans text-sm font-semibold text-ink-900">
            <a href="{{ route('products.show', $product) }}" class="hover:text-primary">
                {{ $product->name }}
            </a>
        </h3>
        <x-price :product="$product" class="mt-1.5" />
    </div>
</article>
