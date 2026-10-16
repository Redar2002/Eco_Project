<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Gabarit HTML commun aux e-mails.
 */
class MailLayout extends Component
{
    public function __construct(
        public ?string $subject = null,
        public ?string $preheader = null,
    ) {}

    public function render(): View
    {
        return view('emails.layout');
    }
}
