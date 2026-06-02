<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Enums\StatusPembayaran;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.recent-activity-widget';

    protected int | string | array $columnSpan = 'full';

    public static function getSort(): int
    {
        return 6;
    }

    public function getActivities(): Collection
    {
        $activities = collect();

        // Ambil 5 penyewaan baru
        $penyewaans = Penyewaan::with('user', 'kamar')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'penyewaan',
                    'icon' => 'heroicon-o-home',
                    'color' => 'blue',
                    'title' => 'Pengajuan Sewa Baru',
                    'description' => "{$item->user->name} mengajukan sewa kamar {$item->kamar->nama}",
                    'time' => $item->created_at,
                    'url' => route('filament.admin.resources.rental-applications.edit', $item),
                ];
            });

        // Ambil 5 pembayaran terbaru
        $pembayarans = Pembayaran::with('tagihan.penyewaan.user')
            ->where('status', StatusPembayaran::Success)
            ->latest('paid_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $userName = $item->tagihan?->penyewaan?->user?->name ?? 'User';
                $amount = number_format($item->jumlah_diterima, 0, ',', '.');
                return [
                    'type' => 'pembayaran',
                    'icon' => 'heroicon-o-banknotes',
                    'color' => 'success',
                    'title' => 'Pembayaran Berhasil',
                    'description' => "{$userName} membayar tagihan sebesar Rp {$amount}",
                    'time' => $item->paid_at ?? $item->updated_at,
                    'url' => route('filament.admin.resources.payments.edit', $item),
                ];
            });

        // Gabungkan dan urutkan berdasarkan waktu terbaru
        return $activities->concat($penyewaans)
            ->concat($pembayarans)
            ->sortByDesc('time')
            ->take(8)
            ->values();
    }
}
