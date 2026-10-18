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
 * Règles :
 *  - un problème d'envoi (SMTP indisponible, adresse refusée…) ne bloque jamais la commande ;
 *  - chaque tentative est journalisée (canal de log par défaut) pour faciliter le diagnostic ;
 *  - l'e-mail de confirmation n'est envoyé qu'une seule fois par commande.
 */
class OrderNotifier
{
    /** À appeler une fois la commande enregistrée : confirmation client + alerte boutique. */
    public function orderPlaced(Order $order): void
    {
        $this->sendConfirmation($order);

        if ($admin = config('shop.admin_email')) {
            $this->send($admin, new NewOrderAdmin($order->loadMissing('items')), $order);
        }
    }

    /**
     * Envoie l'e-mail de confirmation au client, sans jamais l'envoyer deux fois.
     *
     * Anti-doublon : la colonne « confirmation_sent_at » est réservée de façon atomique
     * (UPDATE … WHERE confirmation_sent_at IS NULL) avant l'envoi. Deux appels simultanés
     * (double clic, relance manuelle + tâche planifiée…) ne peuvent donc pas envoyer deux e-mails.
     * En cas d'échec, la réservation est annulée pour permettre une nouvelle tentative.
     *
     * @return bool true si l'e-mail est parti lors de cet appel
     */
    public function sendConfirmation(Order $order): bool
    {
        // La commande doit exister en base (jamais d'e-mail pour une commande non enregistrée)
        if (! $order->exists) {
            Log::warning('Confirmation non envoyée : commande non enregistrée.', ['order' => $order->number]);

            return false;
        }

        if (! filter_var($order->email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Confirmation non envoyée : adresse e-mail absente ou invalide.', ['order' => $order->number]);

            return false;
        }

        $claimed = Order::whereKey($order->getKey())
            ->whereNull('confirmation_sent_at')
            ->toBase()
            ->update(['confirmation_sent_at' => now()]);

        if ($claimed === 0) {
            Log::info('Confirmation déjà envoyée, envoi ignoré.', ['order' => $order->number]);

            return false;
        }

        try {
            Mail::to($order->email, $order->customer_name)->send(new OrderConfirmed($order->loadMissing('items')));
        } catch (Throwable $e) {
            // On libère la réservation : la commande est conservée et pourra être relancée
            Order::whereKey($order->getKey())->toBase()->update(['confirmation_sent_at' => null]);
            $order->confirmation_sent_at = null;

            Log::error('Échec de l\'envoi de la confirmation de commande.', [
                'order' => $order->number,
                'to' => $order->email,
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        $order->confirmation_sent_at = Order::whereKey($order->getKey())->value('confirmation_sent_at');
        Log::info('E-mail de confirmation envoyé.', ['order' => $order->number, 'to' => $order->email]);

        return true;
    }

    /** Information du client lorsque le statut change. */
    public function statusChanged(Order $order): void
    {
        if ($order->email) {
            $this->send($order->email, new OrderStatusUpdated($order->loadMissing('items')), $order);
        }
    }

    private function send(string $to, Mailable $mail, Order $order): void
    {
        try {
            Mail::to($to)->send($mail);
            Log::info('E-mail de commande envoyé.', ['order' => $order->number, 'mail' => class_basename($mail), 'to' => $to]);
        } catch (Throwable $e) {
            Log::error('Échec de l\'envoi d\'un e-mail de commande.', ['order' => $order->number, 'mail' => class_basename($mail), 'to' => $to, 'error' => $e->getMessage()]);
        }
    }
}
