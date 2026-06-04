<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-submit tes yang waktunya habis, berjalan setiap menit
Schedule::command('tes:auto-submit-expired')->everyMinute()->withoutOverlapping();
