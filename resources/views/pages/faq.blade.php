@php
$sections = [
    'Commandes' => [
        ['Comment passer une commande ?', 'Ajoutez vos articles au panier, cliquez sur « Passer la commande » puis renseignez votre nom, téléphone, ville et adresse. Vous pouvez commander avec ou sans compte.'],
        ['Puis-je modifier ou annuler ma commande ?', 'Oui, tant qu\'elle n\'est pas expédiée. Contactez-nous rapidement par téléphone ou WhatsApp en indiquant votre numéro de commande.'],
        ['Comment suivre ma commande ?', 'Si vous avez un compte, rendez-vous dans « Mon compte › Mes commandes ». Sinon, notre équipe vous tient informé(e) par téléphone.'],
    ],
    'Livraison' => [
        ['Quels sont les délais de livraison ?', 'Nous livrons partout au Maroc en 24 à 72 h ouvrées après confirmation de votre commande.'],
        ['Combien coûte la livraison ?', 'La livraison coûte '.format_mad(config('shop.shipping_fee')).' et elle est offerte dès '.format_mad(config('shop.free_shipping_threshold')).' d\'achat.'],
        ['Livrez-vous dans toutes les villes ?', 'Oui, nous livrons dans toutes les villes du Royaume via nos partenaires transporteurs.'],
    ],
    'Paiement' => [
        ['Quels moyens de paiement acceptez-vous ?', 'Le paiement se fait à la livraison, en espèces, directement au livreur. Le paiement par carte bancaire arrive bientôt.'],
        ['Dois-je payer avant de recevoir mon colis ?', 'Non, vous ne payez rien en ligne : vous réglez uniquement à la réception de votre colis.'],
    ],
    'Retours & échanges' => [
        ['Puis-je retourner un article ?', 'Vous disposez de 14 jours après réception pour retourner un article non porté, non lavé et dans son emballage d\'origine.'],
        ['Comment échanger une taille ?', 'Contactez notre service client : nous organisons l\'échange et le passage du livreur.'],
    ],
];
@endphp
<x-app-layout title="FAQ">
    <x-page-header eyebrow="Aide" title="Questions fréquentes" subtitle="Tout ce que vous devez savoir sur vos commandes, la livraison et les retours." />

    <section class="container-shop mt-16 max-w-3xl">
        @foreach ($sections as $section => $items)
            <h2 class="mb-4 mt-12 text-xl font-semibold first:mt-0">{{ $section }}</h2>
            <div class="divide-y divide-ink-100 rounded-3xl border border-ink-100">
                @foreach ($items as [$question, $answer])
                    <div x-data="{ open: false }">
                        <button type="button" @click="open = !open" :aria-expanded="open" class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left font-medium text-ink-900 transition hover:text-primary">
                            {{ $question }}
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-ink-50 transition" :class="open && 'rotate-45 bg-primary text-white'"><x-icon name="plus" class="h-4 w-4" /></span>
                        </button>
                        <div x-show="open" x-cloak x-transition.opacity class="px-6 pb-5 text-ink-600">{{ $answer }}</div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="mt-16 rounded-3xl bg-ink-50 p-8 text-center">
            <h2 class="text-xl font-semibold">Vous n'avez pas trouvé votre réponse ?</h2>
            <p class="mt-2 text-ink-500">Notre équipe est là pour vous aider.</p>
            <x-button :href="route('pages.contact')" class="mt-6" icon="chat">Nous contacter</x-button>
        </div>
    </section>
</x-app-layout>
