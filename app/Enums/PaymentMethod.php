<?php

namespace App\Enums;

/**
 * Moyens de paiement. Pour activer Stripe plus tard : ajouter le cas ici,
 * créer App\Services\Payment\StripeGateway et l'enregistrer dans PaymentManager.
 */
enum PaymentMethod: string
{
    case CashOnDelivery = 'cod';
    // case Stripe = 'stripe';

    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Paiement à la livraison',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Payez en espèces à la réception de votre colis.',
        };
    }
}
