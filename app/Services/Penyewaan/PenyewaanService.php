<?php

namespace App\Services\Penyewaan;

use App\Contracts\Services\PenyewaanServiceInterface;
use App\Contracts\Repositories\PenyewaanRepositoryInterface;
use App\Contracts\Repositories\KamarRepositoryInterface;
use App\DTOs\Penyewaan\PengajuanSewaDTO;
use App\Enums\StatusPenyewaan;
use App\Enums\StatusKamar;
use Illuminate\Support\Facades\DB;

class PenyewaanService implements PenyewaanServiceInterface
{
    public function __construct(
        private PenyewaanRepositoryInterface $penyewaanRepository,
        private KamarRepositoryInterface $kamarRepository
    ) {}

    public function ajukanSewa(PengajuanSewaDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            $kamar = $this->kamarRepository->findById($dto->kamar_id);
            
            if (!$kamar->status->isAvailable()) {
                throw new \Exception('Kamar tidak tersedia untuk disewa.');
            }

            $kode = 'PSW-' . date('Ym') . '-' . rand(10000, 99999);
            
            $penyewaan = $this->penyewaanRepository->create([
                'user_id' => $dto->user_id,
                'kamar_id' => $dto->kamar_id,
                'kode_penyewaan' => $kode,
                'tanggal_masuk' => $dto->tanggal_masuk,
                'durasi_bulan' => $dto->durasi_bulan,
                'status' => StatusPenyewaan::Pending,
                'catatan_penyewa' => $dto->catatan,
                'harga_bulanan_snapshot' => $kamar->harga_bulanan,
                'deposit_amount' => $kamar->harga_deposit,
            ]);

            // Update kamar status
            $this->kamarRepository->update($kamar->id, [
                'status' => StatusKamar::Reserved
            ]);

            return $penyewaan;
        });
    }

    public function approveSewa($id, $adminId)
    {
        return DB::transaction(function () use ($id, $adminId) {
            $penyewaan = $this->penyewaanRepository->update($id, [
                'status' => StatusPenyewaan::Approved,
                'approved_by' => $adminId,
                'tanggal_approval' => now(),
            ]);

            // Generate initial monthly invoices
            \App\Services\InvoiceService::createMonthlyInvoices($penyewaan);

            // Create notification for tenant
            \App\Models\Notifikasi::create([
                'user_id' => $penyewaan->user_id,
                'tipe' => \App\Enums\TipeNotifikasi::PenyewaanApproved,
                'judul' => 'Pengajuan Sewa Disetujui',
                'pesan' => 'Pengajuan sewa Anda untuk kamar ' . $penyewaan->kamar->nama . ' telah disetujui.',
                'read_at' => null,
            ]);

            // Send approval email
            \App\Jobs\SendEmailApprovalJob::dispatch($penyewaan, 'approved');

            return $penyewaan;
        });
    }

    public function rejectSewa($id, $adminId, $catatan = null)
    {
        return DB::transaction(function () use ($id, $adminId, $catatan) {
            $penyewaan = $this->penyewaanRepository->update($id, [
                'status' => StatusPenyewaan::Rejected,
                'approved_by' => $adminId,
                'catatan_admin' => $catatan,
                'tanggal_approval' => now(),
            ]);

            $this->kamarRepository->update($penyewaan->kamar_id, [
                'status' => StatusKamar::Tersedia
            ]);

            // Create notification for tenant
            \App\Models\Notifikasi::create([
                'user_id' => $penyewaan->user_id,
                'tipe' => \App\Enums\TipeNotifikasi::PenyewaanRejected,
                'judul' => 'Pengajuan Sewa Ditolak',
                'pesan' => 'Pengajuan sewa Anda untuk kamar ' . $penyewaan->kamar->nama . ' telah ditolak.' . ($catatan ? ' Catatan: ' . $catatan : ''),
                'read_at' => null,
            ]);

            // Send rejection email
            \App\Jobs\SendEmailApprovalJob::dispatch($penyewaan, 'rejected');

            return $penyewaan;
        });
    }

    public function activateSewa($id)
    {
        return DB::transaction(function () use ($id) {
            $penyewaan = $this->penyewaanRepository->update($id, [
                'status' => StatusPenyewaan::Active,
                'checkin_at' => now(),
            ]);

            $this->kamarRepository->update($penyewaan->kamar_id, [
                'status' => StatusKamar::Terisi
            ]);

            return $penyewaan;
        });
    }

    /**
     * Terbitkan tagihan berikutnya untuk perpanjangan.
     * Jika tambahanBulan >= 6 bulan, diberikan diskon otomatis.
     * 1 bulan = diskon 0%
     * 3 bulan = diskon 0%
     * 6 bulan = diskon 5%
     * 12 bulan = diskon 10%
     * Akan membuat 1 tagihan gabungan (bukan per bulan) agar diskon tidak bisa dicurangi.
     */
    public function terbitkanTagihanBerikutnya($penyewaanId, int $tambahanBulan = 1)
    {
        return DB::transaction(function () use ($penyewaanId, $tambahanBulan) {
            $penyewaan = \App\Models\Penyewaan::with('tagihan')->findOrFail($penyewaanId);

            // 1. Validasi: harus status Active
            if ($penyewaan->status !== StatusPenyewaan::Active) {
                throw new \Exception('Hanya kontrak aktif yang dapat menerbitkan tagihan perpanjangan.');
            }

            // 2. Validasi: tidak ada tunggakan
            $tunggakan = $penyewaan->tagihan()
                ->whereIn('status', [\App\Enums\StatusTagihan::Unpaid, \App\Enums\StatusTagihan::Overdue])
                ->count();

            if ($tunggakan > 0) {
                throw new \Exception("Tidak dapat menerbitkan tagihan. Terdapat {$tunggakan} tagihan yang belum lunas.");
            }

            // 3. Hitung tanggal mulai tagihan baru
            $tagihanTerakhir = $penyewaan->tagihan()
                ->orderByDesc('periode_tahun')
                ->orderByDesc('periode_bulan')
                ->first();

            $startPeriode = $tagihanTerakhir
                ? \Carbon\Carbon::create($tagihanTerakhir->periode_tahun, $tagihanTerakhir->periode_bulan)->addMonth()->startOfMonth()
                : \Carbon\Carbon::parse($penyewaan->tanggal_masuk)->startOfMonth();

            // Cek apakah tagihan untuk periode berikutnya ini sudah pernah dibuat
            $exists = \App\Models\Tagihan::where('penyewaan_id', $penyewaan->id)
                ->where('periode_bulan', $startPeriode->month)
                ->where('periode_tahun', $startPeriode->year)
                ->exists();

            if ($exists) {
                throw new \Exception("Tagihan untuk periode {$startPeriode->format('F Y')} sudah diterbitkan sebelumnya.");
            }

            // 4. Hitung Diskon Grosir Otomatis
            $diskonPersen = match(true) {
                $tambahanBulan >= 12 => 10,
                $tambahanBulan >= 6  => 5,
                default              => 0,
            };

            $hargaBulanan    = $penyewaan->harga_bulanan_snapshot;
            $totalSebelumDiskon = $hargaBulanan * $tambahanBulan;
            $jumlahDiskon    = (int) round($totalSebelumDiskon * $diskonPersen / 100);
            $totalTagihan    = $totalSebelumDiskon - $jumlahDiskon;
            $dueDate         = $startPeriode->copy()->endOfMonth();
            $kodeTagihan     = \App\Services\CodeGeneratorService::generateTagihan();

            // 5. Buat 1 tagihan gabungan (consolidated invoice) — bukan per bulan
            $tagihan = \App\Models\Tagihan::create([
                'penyewaan_id'        => $penyewaan->id,
                'user_id'             => $penyewaan->user_id,
                'kode_tagihan'        => $kodeTagihan,
                'periode_bulan'       => $startPeriode->month,
                'periode_tahun'       => $startPeriode->year,
                'jumlah_tagihan'      => $totalTagihan,
                'jumlah_denda'        => 0,
                'status'              => \App\Enums\StatusTagihan::Unpaid,
                'tanggal_tagihan'     => now()->toDateString(),
                'tanggal_jatuh_tempo' => $dueDate->toDateString(),
                'is_auto_generated'   => false,
                'untuk_durasi_bulan'  => $tambahanBulan,
                'diskon_persen'       => $diskonPersen,
                'jumlah_diskon'       => $jumlahDiskon,
                'catatan'             => $tambahanBulan > 1
                    ? "Tagihan gabungan {$tambahanBulan} bulan" . ($diskonPersen > 0 ? " (diskon {$diskonPersen}%)" : "")
                    : null,
            ]);

            // 6. Kirim notifikasi in-app ke penyewa
            \App\Models\Notifikasi::create([
                'user_id' => $penyewaan->user_id,
                'tipe'    => \App\Enums\TipeNotifikasi::PenyewaanApproved,
                'judul'   => 'Tagihan Perpanjangan Terbit' . ($diskonPersen > 0 ? " 🎉 Diskon {$diskonPersen}%!" : ""),
                'pesan'   => "Tagihan perpanjangan {$tambahanBulan} bulan untuk kamar {$penyewaan->kamar->nama} telah terbit" .
                             ($diskonPersen > 0 ? " dengan diskon {$diskonPersen}% (hemat Rp " . number_format($jumlahDiskon, 0, ',', '.') . ")" : "") .
                             ". Silakan lakukan pembayaran.",
                'read_at' => null,
            ]);

            // 7. Email dikirim otomatis oleh Tagihan::booted() saat record dibuat (InvoiceEmail)

            return $tagihan;
        });
    }
}
