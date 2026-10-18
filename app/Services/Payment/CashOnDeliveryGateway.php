<?php

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use App\Models\Order;

/**
 * Paiement à la livraison : rien à encaisser en ligne,
 * la commande reste « non payée » jusqu'à la livraison.
 */
class CashOnDeliveryGateway implements PaymentGateway
{
    public function method(): PaymentMethod
    {
        return PaymentMethod::CashOnDelivery;
    }

    public function process(Order $order): PaymentResult
    {
        return new PaymentResult(successful: true);
    }
}
