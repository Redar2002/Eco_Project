<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * E-mail récapitulatif envoyé au client une fois sa commande enregistrée.
 * Version HTML (emails.orders.confirmed) + version texte (emails.orders.confirmed-text).
 */
class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmation de votre commande n° {$this->order->number}",
            // Le client peut répondre directement au service client
            replyTo: [new Address(config('shop.contact.email'), config('app.name').' — Service client')],
            tags: ['order-confirmation'],
            metadata: ['order' => $this->order->number],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.confirmed',
            text: 'emails.orders.confirmed-text',
            with: [
                'trackingUrl' => $this->order->trackingUrl(),
                'contact' => config('shop.contact'),
            ],
        );
    }
}
