import Alpine from 'alpinejs';

window.Alpine = Alpine;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;

document.addEventListener('alpine:init', () => {
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

    /**
     * Fiche produit « cinématique » : section active (fond et couleurs),
     * animations d'entrée rejouées au défilement et inclinaison 3D à la souris.
     */
    Alpine.data('productShowcase', (worlds = []) => ({
        worlds,
        current: 0,
        visible: true, // la vitrine est-elle à l'écran (affichage des points de navigation)
        get world() {
            return this.worlds[this.current] ?? this.worlds[0];
        },
        init() {
            const sections = this.$el.querySelectorAll('[data-showcase-section]');
            // Section active : plus de la moitié visible → change l'ambiance
            const active = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) this.current = Number(e.target.dataset.showcaseSection);
                });
            }, { threshold: 0.5 });
            // Animations : rejouées à chaque entrée (amount 0.1)
            const reveal = new IntersectionObserver((entries) => {
                entries.forEach((e) => e.target.classList.toggle('in-view', e.isIntersecting));
            }, { threshold: 0.1 });
            sections.forEach((s) => { active.observe(s); reveal.observe(s); });
            new IntersectionObserver(([e]) => { this.visible = e.isIntersecting; }).observe(this.$el);
        },
        goTo(i) {
            document.querySelector(`[data-showcase-section="${i}"]`)?.scrollIntoView({ behavior: 'smooth' });
        },
    }));

    // Inclinaison 3D amortie (équivalent d'un ressort) : rotateX 15 → -15, rotateY -15 → 15
    Alpine.data('tilt3d', () => ({
        rx: 0, ry: 0, tx: 0, ty: 0, frame: null,
        enabled: window.matchMedia('(hover: hover) and (prefers-reduced-motion: no-preference)').matches,
        move(event) {
            if (!this.enabled) return;
            const r = this.$el.getBoundingClientRect();
            const x = (event.clientX - r.left) / r.width - 0.5;
            const y = (event.clientY - r.top) / r.height - 0.5;
            this.tx = -y * 30;
            this.ty = x * 30;
            this.animate();
        },
        reset() {
            this.tx = 0;
            this.ty = 0;
            this.animate();
        },
        animate() {
            if (this.frame) return;
            const step = () => {
                this.rx += (this.tx - this.rx) * 0.12;
                this.ry += (this.ty - this.ry) * 0.12;
                const target = this.$el.querySelector('[data-tilt]');
                if (target) target.style.transform = `rotateX(${this.rx.toFixed(2)}deg) rotateY(${this.ry.toFixed(2)}deg)`;
                const done = Math.abs(this.tx - this.rx) < 0.05 && Math.abs(this.ty - this.ry) < 0.05;
                this.frame = done ? null : requestAnimationFrame(step);
            };
            this.frame = requestAnimationFrame(step);
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
