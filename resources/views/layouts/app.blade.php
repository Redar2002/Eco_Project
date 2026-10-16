<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="cart-count" content="{{ $cartCount ?? 0 }}">
        <meta name="description" content="{{ $description ?? 'TirrainStyle — mode streetwear et urbaine au Maroc. Livraison partout au Maroc, paiement à la livraison.' }}">

        <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name', 'TirrainStyle') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col" x-data>
        <a href="#contenu" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:shadow">Aller au contenu</a>

        @include('partials.header')

        <main id="contenu" class="flex-1">
            @if (isset($header))
                <div class="border-b border-ink-100 bg-ink-50/60">
                    <div class="container-shop py-8">{{ $header }}</div>
                </div>
            @endif

            @include('partials.flash')

            {{ $slot }}
        </main>

        @include('partials.footer')
        @include('partials.toasts')
    </body>
</html>
