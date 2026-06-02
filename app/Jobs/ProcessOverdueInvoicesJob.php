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
        $overdueInvoices = Tagihan::where('status', StatusTagihan::Unpaid)
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::now())
            ->with('penyewaan.user')
            ->get();

        foreach ($overdueInvoices as $tagihan) {
            $tagihan->update([
                'status' => StatusTagihan::Overdue,
                'jumlah_denda' => $tagihan->jumlah_tagihan * 0.02, // 2% denda
            ]);

            // Create overdue notification
            Notifikasi::create([
                'user_id' => $tagihan->penyewaan->user_id,
                'tipe' => TipeNotifikasi::InvoiceOverdue,
                'judul' => 'Tagihan Sudah Jatuh Tempo',
                'pesan' => 'Tagihan Rp ' . number_format($tagihan->jumlah_tagihan, 0, ',', '.') . ' sudah jatuh tempo sejak ' . Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y'),
                'read_at' => null,
            ]);
        }
    }
}
