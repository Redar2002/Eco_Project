<x-account-layout title="Mon profil" subtitle="Gérez vos informations personnelles et votre mot de passe.">
    <div class="space-y-6">
        <div class="rounded-3xl border border-ink-100 p-6 sm:p-8">
            <div class="max-w-xl">@include('profile.partials.update-profile-information-form')</div>
        </div>
        <div class="rounded-3xl border border-ink-100 p-6 sm:p-8">
            <div class="max-w-xl">@include('profile.partials.update-password-form')</div>
        </div>
        <div class="rounded-3xl border border-red-100 bg-red-50/30 p-6 sm:p-8">
            <div class="max-w-xl">@include('profile.partials.delete-user-form')</div>
        </div>
    </div>
</x-account-layout>
