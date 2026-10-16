<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderNotifier;
use Illuminate\Console\Command;

/**
 * Relance l'envoi des e-mails de confirmation qui ont échoué.
 *
 *   php artisan orders:send-confirmations                 → toutes les commandes en attente d'envoi (7 derniers jours)
 *   php artisan orders:send-confirmations TS-20261016-AB12C → une commande précise
 *
 * Aucune commande n'est créée ni modifiée (hormis la date d'envoi) et une confirmation
 * déjà envoyée n'est jamais renvoyée.
 */
class SendOrderConfirmations extends Command
{
    protected $signature = 'orders:send-confirmations
                            {number? : Numéro d\'une commande précise}
                            {--days=7 : Ancienneté maximale des commandes à relancer}';

    protected $description = 'Envoie les e-mails de confirmation de commande qui n\'ont pas encore pu être envoyés';

    public function handle(OrderNotifier $notifier): int
    {
        $orders = Order::query()
            ->with('items')
            ->whereNull('confirmation_sent_at')
            ->whereNotNull('email')
            ->when(
                $this->argument('number'),
                fn ($q, $number) => $q->where('number', strtoupper($number)),
                fn ($q) => $q->where('created_at', '>=', now()->subDays((int) $this->option('days')))
                    ->where('status', '!=', OrderStatus::Cancelled->value),
            )
            ->oldest()
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Aucune confirmation en attente d\'envoi.');

            return self::SUCCESS;
        }

        $sent = 0;
        foreach ($orders as $order) {
            if ($notifier->sendConfirmation($order)) {
                $sent++;
                $this->line("  <info>✓</info> {$order->number} → {$order->email}");
            } else {
                $this->line("  <error>✗</error> {$order->number} → échec (voir storage/logs/laravel.log)");
            }
        }

        $failed = $orders->count() - $sent;
        $this->newLine();
        $this->info("{$sent} confirmation(s) envoyée(s), {$failed} échec(s).");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
