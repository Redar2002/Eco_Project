<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function __invoke(NewsletterRequest $request): RedirectResponse
    {
        NewsletterSubscriber::firstOrCreate(['email' => mb_strtolower($request->validated('newsletter_email'))]);

        return back()->with('success', 'Merci ! Vous êtes bien inscrit(e) à notre newsletter.');
    }
}
