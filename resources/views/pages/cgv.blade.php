<x-app-layout title="Conditions générales de vente">
    <x-page-header eyebrow="Informations légales" title="Conditions générales de vente" :subtitle="'Dernière mise à jour : '.now()->translatedFormat('F Y')" />

    <article class="container-shop rich-text mt-16 max-w-3xl">
        <x-alert type="info">Ce document est un modèle fourni à titre indicatif. Faites-le valider par un professionnel du droit avant la mise en production.</x-alert>

        <h2>1. Objet</h2>
        <p>Les présentes conditions générales de vente (CGV) régissent les ventes réalisées sur le site TirrainStyle, édité par la société TirrainStyle SARL, dont le siège est situé {{ config('shop.contact.address') }}. Elles s'appliquent conformément à la loi n° 31-08 édictant des mesures de protection du consommateur.</p>

        <h2>2. Produits et prix</h2>
        <p>Les produits sont décrits avec la plus grande exactitude possible. Les prix sont indiqués en dirhams marocains (MAD), toutes taxes comprises, hors frais de livraison. TirrainStyle se réserve le droit de modifier ses prix à tout moment ; le prix applicable est celui affiché au moment de la validation de la commande.</p>

        <h2>3. Commande</h2>
        <p>Toute commande vaut acceptation des présentes CGV. Après validation, un numéro de commande est attribué et notre équipe contacte le client par téléphone pour confirmer la commande. TirrainStyle se réserve le droit d'annuler toute commande en cas de rupture de stock ou d'informations erronées.</p>

        <h2>4. Paiement</h2>
        <p>Le paiement s'effectue à la livraison, en espèces, auprès du livreur. D'autres moyens de paiement pourront être proposés ultérieurement.</p>

        <h2>5. Livraison</h2>
        <ul>
            <li>Zone de livraison : tout le territoire marocain.</li>
            <li>Délai indicatif : 24 à 72 h ouvrées après confirmation.</li>
            <li>Frais : {{ format_mad(config('shop.shipping_fee')) }}, offerts dès {{ format_mad(config('shop.free_shipping_threshold')) }} d'achat.</li>
        </ul>

        <h2>6. Droit de retour</h2>
        <p>Conformément à la loi n° 31-08, le client dispose d'un délai de 14 jours à compter de la réception pour retourner un article non porté, non lavé et dans son emballage d'origine. Le remboursement ou l'échange intervient sous 14 jours après réception du retour.</p>

        <h2>7. Données personnelles</h2>
        <p>Les données collectées sont nécessaires au traitement des commandes. Elles sont traitées conformément à la loi n° 09-08 relative à la protection des personnes physiques à l'égard du traitement des données à caractère personnel. Le client dispose d'un droit d'accès, de rectification et d'opposition en écrivant à {{ config('shop.contact.email') }}.</p>

        <h2>8. Litiges</h2>
        <p>Les présentes CGV sont soumises au droit marocain. En cas de litige, une solution amiable sera recherchée en priorité ; à défaut, les tribunaux compétents de Casablanca seront saisis.</p>
    </article>
</x-app-layout>
