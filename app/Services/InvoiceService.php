<?php

namespace App\Services;

use App\Models\Tagihan;
use App\Models\Penyewaan;
use App\Enums\StatusTagihan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Calculate total overdue amount for a user
     */
    public static function getTotalOverdueAmount(int $userId): float
    {
        return Tagihan::where('user_id', $userId)
            ->where('status', StatusTagihan::Overdue)
            ->sum('jumlah_tagihan');
    }

    /**
     * Get count of overdue invoices for a user
     */
    public static function getOverdueCount(int $userId): int
    {
        return Tagihan::where('user_id', $userId)
            ->where('status', StatusTagihan::Overdue)
            ->count();
    }

    /**
     * Get total paid amount for a user
     */
    public static function getTotalPaidAmount(int $userId): float
    {
        return Tagihan::where('user_id', $userId)
            ->where('status', StatusTagihan::Paid)
            ->sum('jumlah_tagihan');
    }

    /**
     * Get pending invoices for a user
     */
    public static function getPendingInvoices(int $userId)
    {
        return Tagihan::where('user_id', $userId)
            ->whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])
            ->orderBy('tanggal_jatuh_tempo')
            ->get();
    }

    /**
     * Create monthly invoices for a rental
     */
    public static function createMonthlyInvoices(Penyewaan $penyewaan): array
    {
        $invoices = [];
        $startDate = Carbon::parse($penyewaan->tanggal_masuk);
        $monthCount = $penyewaan->durasi_bulan ?? 1;

        for ($i = 0; $i < $monthCount; $i++) {
            $periodeDate = $startDate->copy()->addMonths($i);
            $dueDate     = $periodeDate->copy()->endOfMonth();
            $invoiceDate = $periodeDate->copy()->startOfMonth();

            $kodeTagihan = CodeGeneratorService::generateTagihan();

            $invoice = Tagihan::create([
                'penyewaan_id'       => $penyewaan->id,
                'user_id'            => $penyewaan->user_id,
                'kode_tagihan'       => $kodeTagihan,
                'periode_bulan'      => (int) $periodeDate->format('n'),
                'periode_tahun'      => (int) $periodeDate->format('Y'),
                'jumlah_tagihan'     => $penyewaan->harga_bulanan_snapshot,
                'jumlah_denda'       => 0,
                'total_tagihan'      => $penyewaan->harga_bulanan_snapshot,
                'status'             => StatusTagihan::Unpaid,
                'tanggal_tagihan'    => $invoiceDate->toDateString(),
                'tanggal_jatuh_tempo'=> $dueDate->toDateString(),
                'is_auto_generated'  => true,
            ]);

            $invoices[] = $invoice;
        }

        return $invoices;
    }

    /**
     * Mark overdue invoices (called by scheduler)
     */
    public static function markOverdueInvoices(): int
    {
        return Tagihan::where('status', StatusTagihan::Unpaid)
            ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->update([
                'status'       => StatusTagihan::Overdue,
                'jumlah_denda' => \DB::raw('jumlah_tagihan * 0.02'), // 2% denda
            ]);
    }

    /**
     * Get invoices due in N days (for reminders)
     */
    public static function getInvoicesDueIn(int $days)
    {
        return Tagihan::with(['penyewaan.user', 'penyewaan.kamar'])
            ->whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])
            ->whereDate('tanggal_jatuh_tempo', now()->addDays($days)->toDateString())
            ->get();
    }

    /**
     * Get statistics for dashboard
     */
    public static function getDashboardStats(): array
    {
        return [
            'tagihan_aktif'      => Tagihan::whereIn('status', [StatusTagihan::Unpaid, StatusTagihan::Overdue])->count(),
            'tagihan_jatuh_tempo'=> Tagihan::where('status', StatusTagihan::Overdue)->count(),
            'total_pendapatan'   => Tagihan::where('status', StatusTagihan::Paid)->sum('jumlah_tagihan'),
            'pendapatan_bulan'   => Tagihan::where('status', StatusTagihan::Paid)
                                        ->whereMonth('tanggal_bayar', now()->month)
                                        ->whereYear('tanggal_bayar', now()->year)
                                        ->sum('jumlah_tagihan'),
            'pendapatan_hari'    => Tagihan::where('status', StatusTagihan::Paid)
                                        ->whereDate('tanggal_bayar', today())
                                        ->sum('jumlah_tagihan'),
        ];
    }
}
