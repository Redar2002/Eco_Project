<x-guest-layout>
    <h1 class="text-3xl font-bold tracking-tight">Bon retour 👋</h1>
    <p class="mt-2 text-ink-500">Connectez-vous pour suivre vos commandes.</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <x-input name="email" type="email" label="Adresse e-mail" required autofocus autocomplete="username" placeholder="vous@exemple.ma" />

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="form-label">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a class="mb-1.5 text-xs font-medium text-primary hover:text-primary-950" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                @endif
            </div>
            <x-input name="password" type="password" required autocomplete="current-password" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm text-ink-600">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-ink-300 text-primary focus:ring-primary-600" name="remember">
            Se souvenir de moi
        </label>

        <x-primary-button class="w-full">Se connecter</x-primary-button>
    </form>

    <p class="mt-8 text-center text-sm text-ink-500">
        Pas encore de compte ? <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-primary-950">Créer un compte</a>
    </p>
</x-guest-layout>
