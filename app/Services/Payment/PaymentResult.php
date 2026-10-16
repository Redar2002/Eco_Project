<?php

namespace App\Services\Payment;

/**
 * Résultat d'un traitement de paiement.
 * « redirectUrl » permet à une passerelle en ligne (Stripe…) de renvoyer vers sa page de paiement.
 */
final readonly class PaymentResult
{
    public function __construct(
        public bool $successful = true,
        public ?string $redirectUrl = null,
        public ?string $message = null,
    ) {}
}
