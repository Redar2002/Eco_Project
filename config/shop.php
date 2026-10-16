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

    'contact' => [
        'email' => 'contact@tirrainstyle.ma',
        'phone' => '+212 5 22 00 00 00',
        'whatsapp' => '+212 6 00 00 00 00',
        'address' => 'Bd Anfa, Casablanca, Maroc',
    ],

    // Villes proposées au checkout (champ libre accepté aussi)
    'cities' => [
        'Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès',
        'Oujda', 'Kénitra', 'Tétouan', 'Salé', 'El Jadida', 'Nador', 'Béni Mellal', 'Laâyoune',
    ],
];
