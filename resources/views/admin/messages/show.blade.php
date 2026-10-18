<x-admin-layout :title="'Message de '.$message->name">
    <x-slot name="actions">
        <x-button :href="route('admin.messages.index')" variant="ghost" size="sm" icon="arrow-left">Retour</x-button>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.card :title="$message->subject_label" class="xl:col-span-2">
            <p class="text-xs text-ink-500">Reçu le {{ $message->created_at->translatedFormat('d F Y à H:i') }}</p>
            <div class="mt-4 whitespace-pre-line leading-relaxed text-ink-800">{{ $message->message }}</div>
        </x-admin.card>

        <div class="space-y-6">
            <x-admin.card title="Expéditeur">
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-ink-500">Nom</dt><dd class="font-medium text-ink-900">{{ $message->name }}</dd></div>
                    <div><dt class="text-ink-500">E-mail</dt><dd><a href="mailto:{{ $message->email }}" class="font-medium text-primary">{{ $message->email }}</a></dd></div>
                    @if ($message->phone)<div><dt class="text-ink-500">Téléphone</dt><dd><a href="tel:{{ $message->phone }}" class="font-medium text-primary">{{ $message->phone }}</a></dd></div>@endif
                    <div><dt class="text-ink-500">Compte</dt><dd class="font-medium text-ink-900">{{ $message->user ? 'Client inscrit' : 'Visiteur' }}</dd></div>
                </dl>
                <div class="mt-6 flex flex-col gap-2">
                    <x-button :href="'mailto:'.$message->email.'?subject='.rawurlencode('Re: '.$message->subject_label)" icon="mail">Répondre par e-mail</x-button>
                    @if ($message->phone)
                        <x-button :href="'https://wa.me/'.preg_replace('/^0/', '212', preg_replace('/\D/', '', $message->phone))" variant="secondary" icon="chat" target="_blank">WhatsApp</x-button>
                    @endif
                </div>
            </x-admin.card>

            <div class="flex items-center justify-between gap-3">
                <form method="POST" action="{{ route('admin.messages.unread', $message) }}">
                    @csrf @method('PATCH')
                    <x-button variant="ghost" size="sm" icon="mail">Marquer non lu</x-button>
                </form>
                <x-confirm-delete :action="route('admin.messages.destroy', $message)" name="delete-message" title="Supprimer ce message ?" message="Le message sera définitivement supprimé." />
            </div>
        </div>
    </div>
</x-admin-layout>
