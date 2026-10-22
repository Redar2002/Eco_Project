<?php

/*
| Paramètres de la boutique TirrainStyle.
| Les montants sont exprimés en dirhams marocains (MAD).
*/

return [
    'name' => env('APP_NAME', 'TirrainStyle'),

    'currency' => 'MAD',

    // Frais de livraison standard et seuil de gratuité
    'shipping_fee' => (float) env('SHOP_SHIPPING_FEE', 35),
    'free_shipping_threshold' => (float) env('SHOP_FREE_SHIPPING_THRESHOLD', 500),

    // Quantité maximale d'un même article dans le panier
    'max_quantity_per_item' => 10,

    // Un produit est « Nouveau » s'il a été ajouté il y a moins de N jours
    'new_product_days' => 30,

    // Seuil « stock faible » pour le back-office
    'low_stock_threshold' => 5,

    // Adresse qui reçoit une alerte à chaque nouvelle commande (vide = désactivé)
    'admin_email' => env('SHOP_ADMIN_EMAIL'),

    // Adresse qui reçoit les messages du formulaire de contact.
    // Par défaut : SHOP_ADMIN_EMAIL, sinon l'adresse d'envoi (MAIL_FROM_ADDRESS).
    'contact_inbox' => env('SHOP_CONTACT_INBOX', env('SHOP_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS'))),

    'contact' => [
        'email' => 'contact@tirrainstyle.ma',
        'phone' => '+212 6 75 57 87 20',
        'whatsapp' => '+212 6 75 57 87 20',
        'address' => 'Hay El Maghreb El Arabi, Témara, Maroc',
    ],

    /*
    | Ambiances de la fiche produit « cinématique » (dégradés radiaux).
    | light = true : texte brun foncé ; false : texte blanc.
    */
    'product_worlds' => [
        'ivoire' => [
            'label' => 'Ivoire',
            'gradient' => 'radial-gradient(circle at center, #FFF8EC 0%, #F4E8D0 45%, #B89D6A 100%)',
            'light' => true,
            'particles' => ['#B89D6A', '#FFF8EC', '#D9C08F'],
        ],
        'cacao' => [
            'label' => 'Cacao',
            'gradient' => 'radial-gradient(circle at center, #6B4423 0%, #2A1810 50%, #080403 100%)',
            'light' => false,
            'particles' => ['#C08A5B', '#8B5A2B', '#F4E8D0'],
        ],
        'saphir' => [
            'label' => 'Saphir',
            'gradient' => 'radial-gradient(circle at center, #3B82F6 0%, #172554 45%, #020617 100%)',
            'light' => false,
            'particles' => ['#93C5FD', '#E2E8F0', '#60A5FA'],
        ],
        'pistache' => [
            'label' => 'Pistache',
            'gradient' => 'radial-gradient(circle at center, #CDE8B5 0%, #567A36 45%, #13200B 100%)',
            // Texte blanc : le brun foncé manque de contraste sur le vert profond des bords
            'light' => false,
            'particles' => ['#CDE8B5', '#A3C985', '#F0F7E8'],
        ],
    ],

    // Ambiance de départ de chaque catégorie (les sections suivantes enchaînent les autres)
    'category_worlds' => [
        't-shirts' => 'ivoire',
        'sweats-hoodies' => 'cacao',
        'jeans-pantalons' => 'saphir',
        'vestes' => 'pistache',
        'sneakers' => 'saphir',
        'accessoires' => 'ivoire',
    ],

    // Villes proposées au checkout (champ libre accepté aussi)
    'cities' => [
        'Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès',
        'Oujda', 'Kénitra', 'Tétouan', 'Salé', 'El Jadida', 'Nador', 'Béni Mellal', 'Laâyoune',
    ],
];
