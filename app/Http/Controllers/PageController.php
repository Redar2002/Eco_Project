<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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

    public function sendContact(ContactRequest $request): RedirectResponse
    {
        // En production : envoyer un e-mail (Mail::to(config('shop.contact.email'))->send(...)).
        // Ici, le message est journalisé pour la démonstration.
        Log::info('Nouveau message de contact', $request->safe()->except('website'));

        return to_route('pages.contact')->with('success', 'Merci pour votre message ! Notre équipe vous répondra sous 24 h ouvrées.');
    }
}
