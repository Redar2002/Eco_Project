{{-- Gabarit commun des pages d'erreur --}}
<x-app-layout :title="$title">
    <div class="container-shop flex flex-col items-center py-24 text-center">
        <p class="font-display text-8xl text-accent/40 sm:text-9xl">{{ $code }}</p>
        <h1 class="-mt-6 text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h1>
        <p class="mt-4 max-w-md text-ink-500">{{ $message }}</p>
        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <x-button :href="route('home')" icon="home">Retour à l'accueil</x-button>
            <x-button :href="route('products.index')" variant="outline">Voir la boutique</x-button>
        </div>
    </div>
</x-app-layout>
