<x-app-layout title="Contact">
    <x-page-header eyebrow="Contact" title="Une question ? Parlons-en." subtitle="Notre équipe vous répond sous 24 h ouvrées, du lundi au samedi." />

    <section class="container-shop mt-16 grid gap-10 lg:grid-cols-[1fr_22rem]">
        <div class="rounded-3xl border border-ink-100 p-6 sm:p-10">
            <h2 class="text-2xl font-semibold">Envoyez-nous un message</h2>
            <form action="{{ route('pages.contact.send') }}" method="POST" class="mt-8 grid gap-5 sm:grid-cols-2" x-data="{ sending: false }" @submit="sending = true">
                @csrf
                <x-input name="name" label="Nom complet" :value="auth()->user()?->name" required autocomplete="name" />
                <x-input name="email" type="email" label="Adresse e-mail" :value="auth()->user()?->email" required autocomplete="email" />
                <x-input name="phone" type="tel" label="Téléphone (facultatif)" autocomplete="tel" />
                <x-select name="subject" label="Sujet" required placeholder="Choisir un sujet…" :options="\App\Models\ContactMessage::SUBJECTS" />
                <x-textarea name="message" label="Message" rows="6" required class="sm:col-span-2" placeholder="Comment pouvons-nous vous aider ?" />
                {{-- Champ piège anti-spam, invisible pour les humains --}}
                <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                <div class="sm:col-span-2">
                    <x-button size="lg" ::disabled="sending" icon="mail">Envoyer le message</x-button>
                </div>
            </form>
        </div>

        <aside class="space-y-4">
            @foreach ([
                ['phone', 'Téléphone', config('shop.contact.phone'), 'Lun-Sam, 9h-19h'],
                ['chat', 'WhatsApp', config('shop.contact.whatsapp'), 'Réponse rapide 7j/7'],
                ['mail', 'E-mail', config('shop.contact.email'), 'Réponse sous 24 h'],
                ['map-pin', 'Showroom', config('shop.contact.address'), 'Sur rendez-vous'],
            ] as [$icon, $label, $value, $hint])
                <div class="flex gap-4 rounded-2xl bg-ink-50 p-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-primary shadow-soft"><x-icon :name="$icon" /></span>
                    <div>
                        <p class="text-sm text-ink-500">{{ $label }}</p>
                        <p class="font-semibold text-ink-900">{{ $value }}</p>
                        <p class="text-xs text-ink-400">{{ $hint }}</p>
                    </div>
                </div>
            @endforeach
            <a href="{{ route('pages.faq') }}" class="group flex items-center justify-between rounded-2xl bg-primary p-5 text-white">
                <span><span class="block text-sm text-primary-100">Réponse immédiate</span><span class="font-semibold">Consultez notre FAQ</span></span>
                <x-icon name="arrow-right" class="transition group-hover:translate-x-1" />
            </a>
        </aside>
    </section>
</x-app-layout>
