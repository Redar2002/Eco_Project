<x-app-layout title="Mon panier">
    <div class="container-shop py-10">
        <x-breadcrumbs :items="['Panier' => null]" />
        <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Mon panier</h1>

        @if ($cart->isEmpty())
            <x-empty-state class="mt-10" icon="bag" title="Votre panier est vide" description="Parcourez notre collection et ajoutez vos coups de cœur.">
                <x-button :href="route('products.index')" icon-right="arrow-right">Découvrir la boutique</x-button>
            </x-empty-state>
        @else
            <div class="mt-8 grid gap-10 lg:grid-cols-[1fr_24rem]">
                {{-- ===== Articles ===== --}}
                <div>
                    {{-- Progression vers la livraison gratuite --}}
                    <div class="border border-accent-200 bg-ivory p-4">
                        @if ($cart->remainingForFreeShipping() > 0)
                            <p class="flex items-center gap-2 text-sm text-ink-800">
                                <x-icon name="truck" class="h-5 w-5" />
                                Plus que <strong>{{ format_mad($cart->remainingForFreeShipping()) }}</strong> pour profiter de la livraison gratuite !
                            </p>
                        @else
                            <p class="flex items-center gap-2 text-sm font-semibold text-green-700">
                                <x-icon name="check-circle" class="h-5 w-5" /> Félicitations, la livraison vous est offerte !
                            </p>
                        @endif
                        <div class="mt-3 h-1 overflow-hidden bg-ink-200">
                            <div class="h-full bg-gold-gradient transition-all duration-700" style="width: {{ $cart->freeShippingProgress() }}%"></div>
                        </div>
                    </div>

                    <ul class="mt-6 divide-y divide-ink-100 border-y border-ink-100">
                        @foreach ($cart->lines as $line)
                            <li class="flex gap-4 py-6 sm:gap-6">
                                <a href="{{ route('products.show', $line->product) }}" class="h-32 w-24 shrink-0 overflow-hidden bg-ink-100 sm:h-36 sm:w-28">
                                    <img src="{{ $line->product->image_url }}" alt="{{ $line->product->name }}" class="h-full w-full object-cover">
                                </a>
                                <div class="flex flex-1 flex-col">
                                    <div class="flex justify-between gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-wider text-ink-400">{{ $line->product->category->name }}</p>
                                            <a href="{{ route('products.show', $line->product) }}" class="mt-0.5 block font-semibold text-ink-900 hover:text-accent-700">{{ $line->product->name }}</a>
                                            <x-price :product="$line->product" size="sm" class="mt-1" />
                                        </div>
                                        <p class="text-right font-bold text-ink-900">{{ format_mad($line->lineTotal) }}</p>
                                    </div>

                                    <div class="mt-auto flex items-center justify-between pt-4">
                                        {{-- Quantité : chaque bouton envoie la nouvelle quantité --}}
                                        <form action="{{ route('cart.update', $line->product) }}" method="POST" class="flex items-center border border-ink-300 p-1">
                                            @csrf @method('PATCH')
                                            <button type="submit" name="quantity" value="{{ $line->quantity - 1 }}" class="flex h-8 w-8 items-center justify-center rounded-full text-ink-700 transition hover:bg-ink-100" aria-label="Diminuer la quantité">
                                                <x-icon name="minus" class="h-4 w-4" />
                                            </button>
                                            <span class="w-8 text-center text-sm font-semibold" aria-label="Quantité">{{ $line->quantity }}</span>
                                            <button type="submit" name="quantity" value="{{ $line->quantity + 1 }}" @disabled($line->quantity >= min($line->product->stock, config('shop.max_quantity_per_item')))
                                                    class="flex h-8 w-8 items-center justify-center rounded-full text-ink-700 transition hover:bg-ink-100 disabled:opacity-30" aria-label="Augmenter la quantité">
                                                <x-icon name="plus" class="h-4 w-4" />
                                            </button>
                                        </form>

                                        <form action="{{ route('cart.remove', $line->product) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center gap-1.5 text-sm text-ink-500 transition hover:text-red-600">
                                                <x-icon name="trash" class="h-4 w-4" /> <span class="hidden sm:inline">Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                    @if ($line->product->stock <= config('shop.low_stock_threshold'))
                                        <p class="mt-2 text-xs font-medium text-amber-700">Plus que {{ $line->product->stock }} en stock</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('products.index') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-ink-950 underline decoration-accent decoration-2 underline-offset-4 hover:text-accent-700">
                        <x-icon name="arrow-left" class="h-4 w-4" /> Continuer mes achats
                    </a>
                </div>

                {{-- ===== Récapitulatif ===== --}}
                <aside class="h-fit bg-ink-50 p-6 lg:sticky lg:top-28">
                    <h2 class="text-lg font-semibold">Récapitulatif</h2>
                    <dl class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink-600">Sous-total ({{ $cart->count }} {{ Str::plural('article', $cart->count) }})</dt>
                            <dd class="font-medium text-ink-900">{{ format_mad($cart->subtotal) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-600">Livraison</dt>
                            <dd class="font-medium {{ $cart->shippingFee == 0 ? 'text-green-700' : 'text-ink-900' }}">{{ $cart->shippingFee == 0 ? 'Offerte' : format_mad($cart->shippingFee) }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-ink-200 pt-4 text-base">
                            <dt class="font-semibold text-ink-900">Total</dt>
                            <dd class="font-bold text-ink-900">{{ format_mad($cart->total) }}</dd>
                        </div>
                    </dl>
                    <x-button :href="route('checkout.index')" size="lg" class="mt-6 w-full" icon="lock">Passer la commande</x-button>
                    <ul class="mt-6 space-y-2 text-xs text-ink-500">
                        <li class="flex items-center gap-2"><x-icon name="cash" class="h-4 w-4 text-accent-600" /> Paiement à la livraison</li>
                        <li class="flex items-center gap-2"><x-icon name="truck" class="h-4 w-4 text-accent-600" /> Livraison en 24-72 h partout au Maroc</li>
                        <li class="flex items-center gap-2"><x-icon name="refresh" class="h-4 w-4 text-accent-600" /> Retours gratuits sous 14 jours</li>
                    </ul>
                </aside>
            </div>
        @endif
    </div>
</x-app-layout>
