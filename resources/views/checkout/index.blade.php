<x-app-layout title="Finaliser ma commande">
    <div class="container-shop py-10">
        {{-- Étapes --}}
        <ol class="flex items-center gap-2 text-xs font-semibold sm:text-sm">
            <li class="flex items-center gap-2 text-ink-400"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-700"><x-icon name="check" class="h-3.5 w-3.5" stroke="2.5" /></span> Panier</li>
            <li class="h-px w-8 bg-ink-200"></li>
            <li class="flex items-center gap-2 text-primary"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-white">2</span> Livraison & paiement</li>
            <li class="h-px w-8 bg-ink-200"></li>
            <li class="flex items-center gap-2 text-ink-400"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-ink-100">3</span> Confirmation</li>
        </ol>

        <h1 class="mt-6 text-3xl font-bold tracking-tight sm:text-4xl">Finaliser ma commande</h1>

        @guest
            <x-alert type="info" class="mt-6">
                Déjà client ? <a href="{{ route('login') }}" class="font-semibold underline">Connectez-vous</a> pour retrouver vos commandes dans votre espace. Vous pouvez aussi commander sans compte.
            </x-alert>
        @endguest

        @if ($errors->any())
            <x-alert type="error" title="Merci de corriger les erreurs ci-dessous." class="mt-6" />
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="mt-8 grid gap-10 lg:grid-cols-[1fr_24rem]" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf
            <div class="space-y-10">
                {{-- Coordonnées --}}
                <section>
                    <h2 class="flex items-center gap-3 text-lg font-semibold"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-50 text-primary"><x-icon name="user" class="h-4 w-4" /></span> Vos coordonnées</h2>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <x-input name="customer_name" label="Nom complet" :value="$user?->name" required autocomplete="name" class="sm:col-span-2" />
                        <x-input name="phone" type="tel" label="Téléphone" :value="$user?->phone" required autocomplete="tel" placeholder="06 12 34 56 78" hint="Le livreur vous appellera avant la livraison." />
                        <x-input name="email" type="email" label="E-mail (facultatif)" :value="$user?->email" autocomplete="email" hint="Pour recevoir la confirmation." />
                    </div>
                </section>

                {{-- Adresse --}}
                <section>
                    <h2 class="flex items-center gap-3 text-lg font-semibold"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-50 text-primary"><x-icon name="map-pin" class="h-4 w-4" /></span> Adresse de livraison</h2>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input name="city" label="Ville" required list="cities" autocomplete="address-level2" placeholder="Casablanca" />
                            <datalist id="cities">
                                @foreach (config('shop.cities') as $city)<option value="{{ $city }}">@endforeach
                            </datalist>
                        </div>
                        <x-input name="address" label="Adresse" required autocomplete="street-address" placeholder="Rue, numéro, quartier…" />
                        <x-textarea name="notes" label="Instructions de livraison (facultatif)" rows="3" placeholder="Étage, point de repère, horaires…" class="sm:col-span-2" />
                    </div>
                </section>

                {{-- Paiement --}}
                <section>
                    <h2 class="flex items-center gap-3 text-lg font-semibold"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-50 text-primary"><x-icon name="card" class="h-4 w-4" /></span> Mode de paiement</h2>
                    <div class="mt-5 space-y-3">
                        @foreach ($paymentMethods as $value => $gateway)
                            <label class="flex cursor-pointer items-start gap-4 rounded-2xl border-2 border-ink-200 p-4 transition has-[:checked]:border-primary has-[:checked]:bg-primary-50/50">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="mt-1 h-4 w-4 border-ink-300 text-primary focus:ring-primary-600" @checked(old('payment_method', array_key_first($paymentMethods)) === $value)>
                                <span class="flex-1">
                                    <span class="flex items-center gap-2 font-semibold text-ink-900"><x-icon name="cash" class="h-5 w-5 text-primary" /> {{ $gateway->method()->label() }}</span>
                                    <span class="mt-1 block text-sm text-ink-500">{{ $gateway->method()->description() }}</span>
                                </span>
                            </label>
                        @endforeach
                        {{-- Emplacement prévu pour le paiement en ligne (Stripe) --}}
                        <div class="flex items-start gap-4 rounded-2xl border-2 border-dashed border-ink-200 p-4 opacity-60">
                            <span class="mt-1 h-4 w-4 rounded-full border border-ink-300"></span>
                            <span>
                                <span class="flex items-center gap-2 font-semibold text-ink-900"><x-icon name="card" class="h-5 w-5" /> Carte bancaire</span>
                                <span class="mt-1 block text-sm text-ink-500">Bientôt disponible.</span>
                            </span>
                        </div>
                        @error('payment_method')<p class="text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                </section>
            </div>

            {{-- Récapitulatif --}}
            <aside class="h-fit rounded-3xl bg-ink-50 p-6 lg:sticky lg:top-28">
                <h2 class="text-lg font-semibold">Votre commande</h2>
                <ul class="mt-5 space-y-4">
                    @foreach ($cart->lines as $line)
                        <li class="flex items-center gap-3">
                            <div class="relative h-16 w-14 shrink-0 overflow-hidden rounded-lg bg-white">
                                <img src="{{ $line->product->image_url }}" alt="" class="h-full w-full object-cover">
                                <span class="absolute -right-0 -top-0 flex h-5 min-w-5 items-center justify-center rounded-bl-lg bg-ink-900 px-1 text-[11px] font-bold text-white">{{ $line->quantity }}</span>
                            </div>
                            <p class="flex-1 text-sm font-medium text-ink-900">{{ $line->product->name }}</p>
                            <p class="text-sm font-semibold">{{ format_mad($line->lineTotal) }}</p>
                        </li>
                    @endforeach
                </ul>
                <dl class="mt-6 space-y-3 border-t border-ink-200 pt-5 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-600">Sous-total</dt><dd class="font-medium">{{ format_mad($cart->subtotal) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-600">Livraison</dt><dd class="font-medium {{ $cart->shippingFee == 0 ? 'text-green-700' : '' }}">{{ $cart->shippingFee == 0 ? 'Offerte' : format_mad($cart->shippingFee) }}</dd></div>
                    <div class="flex justify-between border-t border-ink-200 pt-4 text-base"><dt class="font-semibold text-ink-900">Total à payer</dt><dd class="font-bold text-ink-900">{{ format_mad($cart->total) }}</dd></div>
                </dl>

                <label class="mt-6 flex items-start gap-3 text-sm text-ink-600">
                    <input type="checkbox" name="terms" value="1" class="mt-0.5 h-4 w-4 rounded border-ink-300 text-primary focus:ring-primary-600" @checked(old('terms'))>
                    <span>J'accepte les <a href="{{ route('pages.cgv') }}" target="_blank" class="font-medium text-primary underline">conditions générales de vente</a>.</span>
                </label>
                @error('terms')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror

                <x-button size="lg" class="mt-6 w-full" ::disabled="submitting">
                    <span x-show="!submitting">Confirmer la commande</span>
                    <span x-show="submitting" x-cloak>Validation…</span>
                </x-button>
                <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-ink-500"><x-icon name="lock" class="h-3.5 w-3.5" /> Vos données sont protégées</p>
            </aside>
        </form>
    </div>
</x-app-layout>
