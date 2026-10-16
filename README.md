# TirrainStyle — Boutique e-commerce Laravel

Boutique en ligne de mode streetwear pour le marché marocain : prix en dirhams (format « 1 299,00 MAD »), paiement à la livraison et interface entièrement en français.

**Stack :** Laravel 13 · PHP 8.2+ · Blade · Tailwind CSS 3 · Alpine.js · Vite · SQLite (MySQL possible) · Laravel Breeze

---

## Fonctionnalités

### Boutique (côté client)
- **Accueil** : hero avec appel à l'action, avantages (livraison, paiement à la livraison, retours), catégories en vedette, produits populaires, bannière promo, nouveautés, newsletter.
- **Catalogue** (`/boutique`) : grille paginée, filtres par catégorie, prix min/max et promotions, tri (nouveautés, prix, nom), recherche par nom, filtres actifs supprimables.
- **Fiche produit** : galerie avec miniatures et zoom plein écran, prix barré en cas de promo, indicateur de stock, sélecteur de quantité, ajout au panier en AJAX avec notification, produits similaires.
- **Panier en session** : modification des quantités, suppression, sous-total, frais de livraison (gratuits au-delà d'un seuil) et barre de progression.
- **Commande** : formulaire (nom, téléphone marocain, ville, adresse), paiement à la livraison, page de confirmation avec numéro de commande (`TS-AAAAMMJJ-XXXXX`).
- **Espace client** : inscription, connexion, tableau de bord, historique des commandes avec frise de suivi, profil.
- **Pages statiques** : À propos, Contact (formulaire validé avec protection anti-spam), FAQ, CGV, pages d'erreur 403, 404, 419 et 500.

### Back-office (`/admin`, rôle admin uniquement)
- **Tableau de bord** : chiffre d'affaires, commandes, clients, ruptures, graphique des ventes sur 14 jours, meilleures ventes, dernières commandes, stocks faibles.
- **Produits** : CRUD complet, plusieurs images avec aperçu, prix et prix promo, stock, catégorie, actif/inactif, mise en avant, recherche et filtres.
- **Catégories** : CRUD avec image, position et mise en vedette.
- **Commandes** : liste filtrable (statut, recherche, dates), détail, changement de statut (en attente, confirmée, expédiée, livrée, annulée). Une annulation remet le stock.
- **Clients** : liste avec nombre de commandes et total dépensé.

---

## Installation

Prérequis : PHP 8.2+ (extensions `pdo_sqlite`, `mbstring`, `fileinfo`), Composer 2, Node.js 18+ et npm.

```bash
git clone <url-du-depot> tirrainstyle && cd tirrainstyle

composer install
npm install

cp .env.example .env
php artisan key:generate

# Base SQLite + données de démonstration (admin, 6 catégories, 30 produits, commandes)
touch database/database.sqlite
php artisan migrate --seed

# Lien public vers storage/app/public (images produits)
php artisan storage:link
```

## Lancement

Dans deux terminaux :

```bash
php artisan serve      # http://127.0.0.1:8000
npm run dev            # Vite (rechargement à chaud)
```

Pour la production, utilisez `npm run build` à la place de `npm run dev`.

### Comptes de démonstration

| Rôle  | E-mail               | Mot de passe |
|-------|----------------------|--------------|
| Admin | `admin@boutique.ma`  | `password`   |
| Client| `client@boutique.ma` | `password`   |

Pour réinitialiser les données : `php artisan migrate:fresh --seed`. Les images placeholder (SVG) sont régénérées à chaque fois.

### Tests

```bash
php artisan test
```

45 tests couvrent : panier, prix recalculés côté serveur, transaction de commande et stock, accès privé aux commandes, contrôle d'accès admin, CRUD, upload d'images, formulaires de contact et de newsletter, authentification Breeze.

---

## Passer à MySQL

Dans `.env` :

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tirrainstyle
DB_USERNAME=root
DB_PASSWORD=
```

Puis lancez `php artisan migrate --seed`. Avec MySQL, le verrouillage `lockForUpdate()` de la commande devient pleinement effectif.

## Configuration de la boutique

`config/shop.php` contient les frais de livraison, le seuil de gratuité, la quantité maximale par article, la durée pendant laquelle un produit reste « Nouveau », le seuil de stock faible, les coordonnées et la liste des villes. Les frais sont aussi réglables dans `.env` :

```dotenv
SHOP_SHIPPING_FEE=35
SHOP_FREE_SHIPPING_THRESHOLD=500
```

---

## Architecture

```
app/
├── Enums/                 OrderStatus, PaymentMethod, PaymentStatus (libellés FR + couleurs)
├── Http/
│   ├── Controllers/       Contrôleurs fins (boutique, Account/, Admin/)
│   ├── Middleware/        EnsureUserIsAdmin (alias « admin »)
│   └── Requests/          Validation (Checkout, Contact, Admin/Product…)
├── Models/                User, Category, Product, ProductImage, Order, OrderItem…
├── Services/
│   ├── Cart/CartService   Panier en session (ids + quantités uniquement)
│   ├── OrderService       Création de commande en transaction + gestion du stock
│   └── Payment/           PaymentGateway, CashOnDeliveryGateway, PaymentManager
├── Support/PlaceholderImage  Génération des visuels SVG de démo
└── helpers.php            format_mad()
resources/views/
├── components/            button, product-card, badge, alert, modal, input, select,
│                          textarea, price, icon, breadcrumbs, confirm-delete, admin/*
├── layouts/               app (boutique), admin (dashboard), guest (authentification)
└── home, products, cart, checkout, account, pages, admin, errors
```

### Sécurité
- **Prix toujours recalculés côté serveur** : la session ne stocke que `product_id => quantité`, et les prix et stocks sont relus en base à chaque affichage et à la commande.
- **Transaction de commande** : produits verrouillés (`lockForUpdate`), vérification du stock, décrément, puis création de la commande. En cas d'échec, rien n'est enregistré.
- Protection CSRF sur tous les formulaires, validation par Form Requests, limitation de débit sur le checkout, le contact et la newsletter.
- Accès admin via la Gate `access-admin` et le middleware `admin`. Le champ `role` n'est pas assignable en masse.
- Confirmation de commande et détail visibles uniquement par leur auteur (session ou compte).

### Ajouter Stripe plus tard
1. `composer require stripe/stripe-php`
2. Ajouter `case Stripe = 'stripe';` dans `App\Enums\PaymentMethod`.
3. Créer `App\Services\Payment\StripeGateway` qui implémente `PaymentGateway` : créer une Checkout Session puis retourner `new PaymentResult(redirectUrl: $session->url)`.
4. L'enregistrer dans `PaymentManager::$gateways`.
5. Ajouter une route de webhook qui passe `payment_status` à `paid`.

Le checkout gère déjà la redirection vers une passerelle externe.
