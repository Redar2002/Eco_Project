<?php

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use InvalidArgumentException;

/**
 * Registre des passerelles de paiement disponibles.
 */
class PaymentManager
{
    /** @var array<class-string<PaymentGateway>> */
    protected array $gateways = [
        CashOnDeliveryGateway::class,
        // StripeGateway::class,
    ];

    /** @return array<string, PaymentGateway> */
    public function available(): array
    {
        return collect($this->gateways)
            ->map(fn (string $class) => app($class))
            ->keyBy(fn (PaymentGateway $gateway) => $gateway->method()->value)
            ->all();
    }

    public function gateway(PaymentMethod $method): PaymentGateway
    {
        return $this->available()[$method->value]
            ?? throw new InvalidArgumentException("Moyen de paiement indisponible : {$method->value}");
    }
}
