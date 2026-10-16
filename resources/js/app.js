import Alpine from 'alpinejs';

window.Alpine = Alpine;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;

document.addEventListener('alpine:init', () => {
    /**
     * x-reveal : fait apparaître un élément en fondu lorsqu'il entre dans l'écran.
     * Usage : <div x-data x-reveal> ou x-reveal="150" pour un délai en ms.
     */
    Alpine.directive('reveal', (el, { expression }) => {
        el.classList.add('reveal');
        if (expression) el.style.transitionDelay = `${parseInt(expression, 10)}ms`;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    el.classList.add('is-visible');
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.12 });
        observer.observe(el);
    });

    // Compteur du panier partagé entre le header et les boutons « Ajouter »
    Alpine.store('cart', {
        count: Number(document.querySelector('meta[name="cart-count"]')?.content || 0),
    });

    // Notifications (toasts) affichées en bas de l'écran
    Alpine.store('toasts', {
        items: [],
        push(toast) {
            const id = Date.now() + Math.random();
            this.items.push({ id, type: 'success', ...toast });
            setTimeout(() => this.remove(id), toast.duration ?? 4500);
        },
        remove(id) {
            this.items = this.items.filter((t) => t.id !== id);
        },
    });

    /**
     * Ajout au panier en AJAX. Sans JavaScript, le formulaire est envoyé normalement
     * et le serveur redirige avec un message flash.
     */
    Alpine.data('addToCart', (maxQuantity = 10) => ({
        quantity: 1,
        loading: false,
        max: maxQuantity,
        increment() {
            if (this.quantity < this.max) this.quantity++;
        },
        decrement() {
            if (this.quantity > 1) this.quantity--;
        },
        async submit(event) {
            const form = event.target;
            this.loading = true;
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quantity: this.quantity }),
                });
                const data = await response.json();
                if (!response.ok) {
                    Alpine.store('toasts').push({ type: 'error', title: 'Oups…', message: data.message ?? 'Une erreur est survenue.' });
                    return;
                }
                Alpine.store('cart').count = data.count;
                Alpine.store('toasts').push({
                    type: 'cart',
                    title: 'Produit ajouté au panier',
                    message: data.product.name,
                    image: data.product.image,
                    meta: `${data.quantity} × ${data.product.price}`,
                });
            } catch (e) {
                form.submit(); // repli : envoi classique
            } finally {
                this.loading = false;
            }
        },
    }));

    // Galerie de la fiche produit
    Alpine.data('gallery', (images = []) => ({
        images,
        active: 0,
        zoom: false,
        select(i) {
            this.active = i;
        },
        next() {
            this.active = (this.active + 1) % this.images.length;
        },
        prev() {
            this.active = (this.active - 1 + this.images.length) % this.images.length;
        },
    }));
});

Alpine.start();
