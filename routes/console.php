<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── KosPro Scheduler ──────────────────────────────────────────
// Generate tagihan bulanan — setiap tanggal 1 jam 07:00
Schedule::command('kostpro:generate-tagihan')->monthlyOn(1, '07:00');

// Cek tagihan overdue — setiap hari jam 08:00
Schedule::command('kostpro:check-overdue')->dailyAt('08:00');

// Kirim reminder H-3 — setiap hari jam 09:00
Schedule::command('kostpro:send-reminder --days=3')->dailyAt('09:00');

// Kirim reminder H-1 — setiap hari jam 09:30
Schedule::command('kostpro:send-reminder --days=1')->dailyAt('09:30');
