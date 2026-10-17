<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function faq(): View
    {
        return view('pages.faq');
    }

    public function cgv(): View
    {
        return view('pages.cgv');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * Enregistre le message (consultable dans l'admin) puis le transmet par e-mail à la boutique.
     * Un échec d'envoi n'empêche pas l'enregistrement : le message reste visible dans l'admin.
     */
    public function sendContact(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'phone', 'subject', 'message']),
            'user_id' => $request->user()?->id,
        ]);

        if ($inbox = config('shop.contact_inbox')) {
            try {
                Mail::to($inbox)->send(new ContactMessageReceived($message));
            } catch (Throwable $e) {
                Log::error('Échec de l\'envoi du message de contact par e-mail.', ['contact_message' => $message->id, 'error' => $e->getMessage()]);
            }
        }

        return to_route('pages.contact')->with('success', 'Merci pour votre message ! Notre équipe vous répondra sous 24 h ouvrées.');
    }
}
