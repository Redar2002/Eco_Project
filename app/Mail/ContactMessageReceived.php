<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Transmet à la boutique un message reçu via le formulaire de contact.
 * « Répondre » dans la messagerie écrit directement au client (Reply-To).
 */
class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Contact] {$this->contactMessage->subject_label} — {$this->contactMessage->name}",
            replyTo: [new Address($this->contactMessage->email, $this->contactMessage->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.received',
            text: 'emails.contact.received-text',
            with: ['adminUrl' => route('admin.messages.show', $this->contactMessage)],
        );
    }
}
