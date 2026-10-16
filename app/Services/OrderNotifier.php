<?php

namespace App\Services;

use App\Mail\NewOrderAdmin;
use App\Mail\OrderConfirmed;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Envoi des e-mails liés aux commandes.
 *
 * Un problème d'envoi (SMTP indisponible, adresse invalide…) ne doit jamais
 * bloquer la commande : l'erreur est simplement journalisée.
 */
class OrderNotifier
{
    /** Confirmation au client + alerte à la boutique. */
    public function orderPlaced(Order $order): void
    {
        $order->loadMissing('items');

        if ($order->email) {
            $this->send($order->email, new OrderConfirmed($order));
        }

        if ($admin = config('shop.admin_email')) {
            $this->send($admin, new NewOrderAdmin($order));
        }
    }

    /** Information du client lorsque le statut change. */
    public function statusChanged(Order $order): void
    {
        if ($order->email) {
            $this->send($order->email, new OrderStatusUpdated($order->loadMissing('items')));
        }
    }

    private function send(string $to, Mailable $mail): void
    {
        try {
            Mail::to($to)->send($mail);
        } catch (Throwable $e) {
            Log::error('Échec de l\'envoi d\'un e-mail de commande', ['to' => $to, 'mail' => $mail::class, 'error' => $e->getMessage()]);
        }
    }
}
