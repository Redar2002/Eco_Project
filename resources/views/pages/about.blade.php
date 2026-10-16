<x-app-layout title="À propos">
    <x-page-header eyebrow="Notre histoire" title="Né à Casablanca, porté par la rue" subtitle="TirrainStyle, c'est la rencontre entre la culture streetwear et l'énergie des villes marocaines." />

    <section class="container-shop mt-20 grid items-center gap-12 lg:grid-cols-2">
        <div class="rich-text text-base">
            <h2 class="!mt-0 text-3xl font-bold tracking-tight text-ink-900">Une marque pensée pour vous</h2>
            <p>Tout a commencé en 2021 dans un petit atelier du Maârif : trois amis passionnés de mode urbaine, frustrés de ne pas trouver au Maroc des pièces à la fois tendance, durables et accessibles.</p>
            <p>Aujourd'hui, TirrainStyle habille des milliers de jeunes de Tanger à Laâyoune. Nos collections sont dessinées à Casablanca et fabriquées avec des ateliers partenaires sélectionnés pour la qualité de leur travail et le respect de leurs équipes.</p>
            <p>Notre promesse reste la même : des coupes actuelles, des matières qui durent et un service client proche de vous.</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @foreach ([['+12 000', 'clients satisfaits'], ['30+', 'références en stock'], ['24-72 h', 'délai de livraison'], ['4,8/5', 'note moyenne']] as [$value, $label])
                <div class=" bg-ink-50 p-6">
                    <p class="font-display text-4xl text-accent-600">{{ $value }}</p>
                    <p class="mt-1 text-sm text-ink-500">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container-shop mt-20">
        <x-section-title eyebrow="Nos valeurs" title="Ce qui nous guide" />
        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach ([
                ['shield', 'Qualité durable', 'Coton épais, coutures renforcées, teintures qui tiennent : nos pièces sont faites pour durer.'],
                ['sparkles', 'Style accessible', 'Des prix justes pour que le style ne soit jamais un luxe.'],
                ['chat', 'Proximité', 'Une équipe marocaine, joignable par téléphone et WhatsApp 7j/7.'],
            ] as [$icon, $title, $text])
                <div class=" border border-ink-100 p-8 transition hover:-translate-y-1 hover:shadow-lift">
                    <span class="flex h-12 w-12 items-center justify-center bg-ink-950 text-accent"><x-icon :name="$icon" class="h-6 w-6" /></span>
                    <h3 class="mt-5 text-lg font-semibold">{{ $title }}</h3>
                    <p class="mt-2 text-ink-500">{{ $text }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-12 text-center">
            <x-button :href="route('products.index')" size="lg" icon-right="arrow-right">Découvrir la collection</x-button>
        </div>
    </section>
</x-app-layout>
