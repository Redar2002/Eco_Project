<x-admin-layout :title="'Commande '.$order->number">
    <x-slot name="actions">
        <x-button :href="route('admin.orders.index')" variant="ghost" size="sm" icon="arrow-left">Retour</x-button>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <x-admin.card title="Suivi">
                @include('partials.order-timeline', ['order' => $order])
            </x-admin.card>

            <x-admin.card title="Articles" :padding="false">
                @include('partials.order-items', ['order' => $order, 'admin' => true])
            </x-admin.card>
        </div>

        <div class="space-y-6">
            {{-- Changement de statut --}}
            <x-admin.card title="Statut de la commande">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-ink-500">Actuel :</span>
                    <x-badge :color="$order->status->color()">{{ $order->status->label() }}</x-badge>
                </div>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
                    @csrf @method('PATCH')
                    <x-select name="status" label="Nouveau statut" :options="\App\Enums\OrderStatus::options()" :value="$order->status->value" />
                    <input type="hidden" name="notify" value="0">
                    <label class="flex items-start gap-3 text-sm text-ink-700">
                        <input type="checkbox" name="notify" value="1" checked class="mt-0.5 h-4 w-4 rounded border-ink-300 text-primary focus:ring-primary-600" @disabled(! $order->email)>
                        <span>Prévenir le client par e-mail
                            <span class="block text-xs text-ink-500">{{ $order->email ?: 'Aucune adresse e-mail pour cette commande' }}</span>
                        </span>
                    </label>
                    <x-button class="w-full">Mettre à jour</x-button>
                    <p class="text-xs text-ink-500">L'annulation remet automatiquement les articles en stock.</p>
                </form>
            </x-admin.card>

            <x-admin.card title="Client">
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-ink-500">Nom</dt><dd class="font-medium text-ink-900">{{ $order->customer_name }}</dd></div>
                    <div><dt class="text-ink-500">Téléphone</dt><dd><a href="tel:{{ $order->phone }}" class="font-medium text-primary">{{ $order->phone }}</a></dd></div>
                    @if ($order->email)<div><dt class="text-ink-500">E-mail</dt><dd class="font-medium text-ink-900">{{ $order->email }}</dd></div>@endif
                    <div>
                        <dt class="text-ink-500">E-mail de confirmation</dt>
                        <dd class="mt-1">
                            @if ($order->confirmation_sent_at)
                                <x-badge color="green">Envoyé le {{ $order->confirmation_sent_at->translatedFormat('d/m/Y à H:i') }}</x-badge>
                            @elseif ($order->email)
                                <x-badge color="red">Non envoyé</x-badge>
                                <form method="POST" action="{{ route('admin.orders.confirmation', $order) }}" class="mt-2">
                                    @csrf
                                    <x-button size="sm" variant="secondary" icon="mail">Renvoyer la confirmation</x-button>
                                </form>
                            @else
                                <span class="text-ink-500">Aucune adresse e-mail</span>
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-ink-500">Compte</dt><dd class="font-medium text-ink-900">{{ $order->user ? 'Client inscrit' : 'Invité' }}</dd></div>
                </dl>
            </x-admin.card>

            <x-admin.card title="Livraison & paiement">
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-ink-500">Adresse</dt><dd class="font-medium text-ink-900">{{ $order->address }}, {{ $order->city }}</dd></div>
                    @if ($order->notes)<div><dt class="text-ink-500">Instructions</dt><dd class="rounded-xl bg-ink-50 p-3 text-ink-700">{{ $order->notes }}</dd></div>@endif
                    <div><dt class="text-ink-500">Paiement</dt><dd class="font-medium text-ink-900">{{ $order->payment_method->label() }}</dd></div>
                    <div><dt class="text-ink-500">État du paiement</dt><dd><x-badge :color="$order->payment_status->value === 'paid' ? 'green' : 'slate'">{{ $order->payment_status->label() }}</x-badge></dd></div>
                    <div><dt class="text-ink-500">Date</dt><dd class="font-medium text-ink-900">{{ $order->created_at->translatedFormat('d F Y à H:i') }}</dd></div>
                </dl>
            </x-admin.card>
        </div>
    </div>
</x-admin-layout>
