{{-- Notifications Alpine.js (ex. « Produit ajouté au panier ») --}}
<div x-data aria-live="polite" class="pointer-events-none fixed inset-x-0 bottom-0 z-[60] flex flex-col items-center gap-3 p-4 sm:items-end sm:p-6">
    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div class="pointer-events-auto w-full max-w-sm animate-slide-in overflow-hidden rounded-2xl bg-white shadow-lift ring-1 ring-ink-900/5">
            <div class="flex items-start gap-3 p-4">
                <template x-if="toast.image">
                    <img :src="toast.image" alt="" class="h-16 w-14 rounded-lg bg-ink-100 object-cover">
                </template>
                <template x-if="!toast.image">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full"
                         :class="toast.type === 'error' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path x-show="toast.type !== 'error'" stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            <path x-show="toast.type === 'error'" stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </div>
                </template>
                <div class="min-w-0 flex-1">
                    <p class="flex items-center gap-1.5 text-sm font-semibold text-ink-900">
                        <svg x-show="toast.type === 'cart'" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        <span x-text="toast.title"></span>
                    </p>
                    <p class="mt-0.5 truncate text-sm text-ink-600" x-text="toast.message"></p>
                    <p x-show="toast.meta" class="mt-0.5 text-xs text-ink-400" x-text="toast.meta"></p>
                    <div x-show="toast.type === 'cart'" class="mt-3 flex gap-2">
                        <a href="{{ route('cart.index') }}" class="rounded-full bg-primary px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-950">Voir le panier</a>
                        <a href="{{ route('checkout.index') }}" class="rounded-full bg-ink-100 px-4 py-1.5 text-xs font-semibold text-ink-900 hover:bg-ink-200">Commander</a>
                    </div>
                </div>
                <button type="button" @click="$store.toasts.remove(toast.id)" class="rounded-full p-1 text-ink-400 hover:bg-ink-100 hover:text-ink-700" aria-label="Fermer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </template>
</div>
