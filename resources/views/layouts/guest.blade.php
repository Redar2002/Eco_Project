<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TirrainStyle') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        {{-- Écran d'authentification en deux colonnes : visuel de marque + formulaire --}}
        <div class="grid min-h-screen lg:grid-cols-2">
            <div class="relative hidden overflow-hidden bg-primary-950 lg:block">
                <div class="absolute -left-24 top-1/4 h-96 w-96 rounded-full bg-primary-600/40 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-accent/20 blur-3xl"></div>
                <div class="relative flex h-full flex-col justify-between p-12 text-white">
                    <a href="{{ route('home') }}"><x-application-logo light /></a>
                    <div>
                        <p class="font-display text-4xl font-bold leading-tight">Rejoignez la communauté<br><span class="text-accent">TirrainStyle.</span></p>
                        <ul class="mt-8 space-y-4 text-primary-100">
                            <li class="flex items-center gap-3"><x-icon name="clipboard" class="text-accent" /> Suivez vos commandes en temps réel</li>
                            <li class="flex items-center gap-3"><x-icon name="sparkles" class="text-accent" /> Accédez aux ventes privées</li>
                            <li class="flex items-center gap-3"><x-icon name="truck" class="text-accent" /> Commandez plus vite grâce à vos infos enregistrées</li>
                        </ul>
                    </div>
                    <p class="text-sm text-primary-200/60">&copy; {{ date('Y') }} TirrainStyle</p>
                </div>
            </div>

            <div class="flex flex-col px-4 py-10 sm:px-8">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="lg:hidden"><x-application-logo /></a>
                    <a href="{{ route('home') }}" class="ml-auto inline-flex items-center gap-1.5 text-sm font-medium text-ink-500 hover:text-ink-900"><x-icon name="arrow-left" class="h-4 w-4" /> Retour à la boutique</a>
                </div>
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
