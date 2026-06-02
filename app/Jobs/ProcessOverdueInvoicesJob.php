<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Models\Notifikasi;
use App\Enums\StatusTagihan;
use App\Enums\TipeNotifikasi;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessOverdueInvoicesJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Get overdue invoices and update to Overdue status
        $overdueInvoices = Tagihan::where('status', StatusTagihan::Pending)
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::now())
            ->with('penyewaan.user')
            ->get();

        foreach ($overdueInvoices as $tagihan) {
            $tagihan->update(['status' => StatusTagihan::Overdue]);

            // Create overdue notification
            Notifikasi::create([
                'user_id' => $tagihan->penyewaan->user_id,
                'tipe' => TipeNotifikasi::InvoiceOverdue,
                'judul' => 'Tagihan Sudah Jatuh Tempo',
                'pesan' => 'Tagihan Rp ' . number_format($tagihan->nominal, 0, ',', '.') . ' sudah jatuh tempo sejak ' . $tagihan->tanggal_jatuh_tempo->format('d M Y'),
                'read_at' => null,
            ]);
        }
    }
}
