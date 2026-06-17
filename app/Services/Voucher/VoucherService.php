<?php

namespace App\Services\Voucher;

use App\Enums\StatusTagihan;
use App\Enums\StatusVoucher;
use App\Enums\TipeNotifikasi;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class VoucherService
{
    public function getActiveVouchersForUser(int $userId): Collection
    {
        $this->expireOutdatedVouchers($userId);

        return Voucher::aktif()
            ->where('user_id', $userId)
            ->orderByDesc('nominal_diskon')
            ->orderBy('berlaku_sampai')
            ->get();
    }

    public function resolveForPayment(int $voucherId, int $userId, Tagihan $tagihan): Voucher
    {
        $voucher = Voucher::aktif()
            ->where('id', $voucherId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $voucher;
    }

    public function calculatePaymentAmount(Tagihan $tagihan, ?Voucher $voucher = null): array
    {
        $total = $tagihan->total_tagihan;
        $diskon = 0;

        if ($voucher && $voucher->isUsable()) {
            $diskon = min($voucher->nominal_diskon, $total);
        }

        return [
            'jumlah' => max(0, $total - $diskon),
            'diskon_voucher' => $diskon,
        ];
    }

    public function markAsUsed(Voucher $voucher, Pembayaran $pembayaran): void
    {
        if ($voucher->status !== StatusVoucher::Aktif) {
            return;
        }

        $voucher->update([
            'status' => StatusVoucher::Digunakan,
            'tagihan_id' => $pembayaran->tagihan_id,
            'pembayaran_id' => $pembayaran->id,
            'digunakan_pada' => now(),
        ]);
    }

    public function generateForOnTimePayment(Pembayaran $pembayaran): ?Voucher
    {
        $tagihan = $pembayaran->tagihan;
        if (!$tagihan || !$pembayaran->paid_at) {
            return null;
        }

        if ($pembayaran->paid_at->gt($tagihan->tanggal_jatuh_tempo->endOfDay())) {
            return null;
        }

        $user = $tagihan->user;
        if (!$user) {
            return null;
        }

        $nominal = $this->determineNominal($user);
        $berlakuHari = (int) config('app.kostpro_voucher_berlaku_hari', 30);

        $voucher = Voucher::create([
            'user_id' => $user->id,
            'kode_voucher' => $this->generateKode(),
            'nominal_diskon' => $nominal,
            'status' => StatusVoucher::Aktif,
            'sumber' => $this->determineSumber($user),
            'pembayaran_sumber_id' => $pembayaran->id,
            'berlaku_sampai' => now()->addDays($berlakuHari),
        ]);

        Notifikasi::create([
            'user_id' => $user->id,
            'tipe' => TipeNotifikasi::Sistem,
            'judul' => 'Voucher Apresiasi Baru! 🎉',
            'pesan' => "Selamat! Anda mendapat voucher apresiasi Rp " . number_format($nominal, 0, ',', '.') .
                       " karena membayar tepat waktu. Kode: {$voucher->kode_voucher}. Berlaku hingga " .
                       $voucher->berlaku_sampai->format('d M Y') . ".",
            'read_at' => null,
        ]);

        return $voucher;
    }

    public function expireOutdatedVouchers(?int $userId = null): void
    {
        $query = Voucher::where('status', StatusVoucher::Aktif)
            ->where('berlaku_sampai', '<', now());

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $query->update(['status' => StatusVoucher::Kadaluarsa]);
    }

    protected function determineNominal(User $user): int
    {
        $streakMin = (int) config('app.kostpro_voucher_streak_min', 3);
        $previousStreak = $this->countOnTimeStreak($user);

        if ($previousStreak >= ($streakMin - 1)) {
            return (int) config('app.kostpro_voucher_streak_nominal', 25000);
        }

        return (int) config('app.kostpro_voucher_nominal', 10000);
    }

    protected function determineSumber(User $user): string
    {
        $streakMin = (int) config('app.kostpro_voucher_streak_min', 3);
        $previousStreak = $this->countOnTimeStreak($user);

        if ($previousStreak >= ($streakMin - 1)) {
            return 'streak_tepat_waktu';
        }

        return 'pembayaran_tepat_waktu';
    }

    protected function countOnTimeStreak(User $user): int
    {
        $paidTagihans = Tagihan::where('user_id', $user->id)
            ->where('status', StatusTagihan::Paid)
            ->whereNotNull('tanggal_bayar')
            ->orderByDesc('tanggal_bayar')
            ->with('pembayaran')
            ->take((int) config('app.kostpro_voucher_streak_min', 3))
            ->get();

        $streak = 0;

        foreach ($paidTagihans as $tagihan) {
            $pembayaran = $tagihan->pembayaran;
            if ($pembayaran && $pembayaran->paid_at && $pembayaran->paid_at <= $tagihan->tanggal_jatuh_tempo->endOfDay()) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    protected function generateKode(): string
    {
        do {
            $kode = 'VCH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Voucher::where('kode_voucher', $kode)->exists());

        return $kode;
    }

    public function generateUniqueKode(): string
    {
        return $this->generateKode();
    }
}
