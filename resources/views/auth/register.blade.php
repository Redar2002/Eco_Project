<x-guest-layout>
    <h1 class="text-3xl font-bold tracking-tight">Créer un compte</h1>
    <p class="mt-2 text-ink-500">Quelques secondes suffisent pour rejoindre TirrainStyle.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf

        <x-input name="name" label="Nom complet" required autofocus autocomplete="name" />
        <x-input name="email" type="email" label="Adresse e-mail" required autocomplete="username" placeholder="vous@exemple.ma" />
        <x-input name="phone" type="tel" label="Téléphone (facultatif)" autocomplete="tel" placeholder="0612345678" />
        <div class="grid gap-5 sm:grid-cols-2">
            <x-input name="password" type="password" label="Mot de passe" required autocomplete="new-password" />
            <x-input name="password_confirmation" type="password" label="Confirmation" required autocomplete="new-password" />
        </div>

        <x-primary-button class="w-full">Créer mon compte</x-primary-button>
    </form>

    <p class="mt-8 text-center text-sm text-ink-500">
        Déjà inscrit ? <a href="{{ route('login') }}" class="font-semibold text-ink-950 underline decoration-accent decoration-2 underline-offset-4 hover:text-accent-700">Se connecter</a>
    </p>
</x-guest-layout>
