<?php

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use App\Models\Order;

/**
 * Contrat commun à tous les moyens de paiement.
 *
 * Pour ajouter Stripe :
 *  1. ajouter `case Stripe = 'stripe';` dans App\Enums\PaymentMethod ;
 *  2. créer StripeGateway implémentant cette interface (création d'une Checkout Session
 *     puis PaymentResult avec redirectUrl) ;
 *  3. l'enregistrer dans PaymentManager::$gateways et gérer le webhook de confirmation.
 */
interface PaymentGateway
{
    public function method(): PaymentMethod;

    public function process(Order $order): PaymentResult;
}
