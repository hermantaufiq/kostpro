<?php

use App\Jobs\SendInvoiceReminderJob;
use App\Jobs\ProcessOverdueInvoicesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── KosPro Scheduler ──────────────────────────────────────────
// Process overdue invoices — setiap hari jam 07:00
Schedule::job(new ProcessOverdueInvoicesJob())->dailyAt('07:00');

// Send invoice reminders — setiap hari jam 08:00
Schedule::job(new SendInvoiceReminderJob())->dailyAt('08:00');

// Send kontrak reminders — setiap hari jam 09:00
Schedule::command('kostpro:send-kontrak-reminder')->dailyAt('09:00');
