{{-- Messages flash (succès / erreur) affichés en haut de page --}}
@if (session('success') || session('error') || session('status') || session('warning'))
    <div class="container-shop mt-6 space-y-3">
        @if (session('success'))
            <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
        @endif
        @if (session('warning'))
            <x-alert type="warning" dismissible>{{ session('warning') }}</x-alert>
        @endif
        @if (session('status') && ! in_array(session('status'), ['profile-updated', 'password-updated', 'verification-link-sent'], true))
            <x-alert type="info" dismissible>{{ session('status') }}</x-alert>
        @endif
    </div>
@endif
