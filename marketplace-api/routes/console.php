<?php

use App\Jobs\CheckExpiringSubscriptions;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Scheduled Jobs ────────────────────────────────────
Schedule::job(new CheckExpiringSubscriptions)
    ->daily()
    ->at('08:00')
    ->name('check-expiring-subscriptions')
    ->withoutOverlapping()
    ->onOneServer();
