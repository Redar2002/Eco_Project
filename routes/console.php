<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Relance automatique des e-mails de confirmation en échec (nécessite « php artisan schedule:work »
// en local, ou la tâche cron « * * * * * php artisan schedule:run » en production)
Schedule::command('orders:send-confirmations')->everyFifteenMinutes()->withoutOverlapping();
