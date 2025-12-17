<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Optional cleanup: keep the availabilities table small.
Schedule::command('app:prune-old-availability --days=30')->dailyAt('02:10');
