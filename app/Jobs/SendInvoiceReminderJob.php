<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Enums\StatusTagihan;
use App\Enums\TipeNotifikasi;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendInvoiceReminderJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Get invoices due in 3 days
        $upcomingInvoices = Tagihan::where('status', '!=', StatusTagihan::Paid)
            ->whereDate('tanggal_jatuh_tempo', '<=', Carbon::now()->addDays(3))
            ->whereDate('tanggal_jatuh_tempo', '>', Carbon::now())
            ->with('penyewaan.user')
            ->get();

        foreach ($upcomingInvoices as $tagihan) {
            Notifikasi::create([
                'user_id' => $tagihan->penyewaan->user_id,
                'tipe' => TipeNotifikasi::TagihanReminder,
                'judul' => 'Pengingat Tagihan',
                'pesan' => 'Tagihan sebesar Rp ' . number_format($tagihan->jumlah_tagihan, 0, ',', '.') . ' akan jatuh tempo pada ' . Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y'),
                'read_at' => null,
            ]);
        }
    }
}
