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

    public function perpanjangKontrak($penyewaanId, int $tambahanBulan, $adminId = null)
    {
        return DB::transaction(function () use ($penyewaanId, $tambahanBulan, $adminId) {
            $penyewaan = \App\Models\Penyewaan::with('tagihan')->findOrFail($penyewaanId);

            // 1. Validasi: harus status Active
            if ($penyewaan->status !== StatusPenyewaan::Active) {
                throw new \Exception('Hanya kontrak aktif yang dapat diperpanjang.');
            }

            // 2. Validasi: tidak ada tunggakan
            $tunggakan = $penyewaan->tagihan()
                ->whereIn('status', [\App\Enums\StatusTagihan::Unpaid, \App\Enums\StatusTagihan::Overdue])
                ->count();

            if ($tunggakan > 0) {
                throw new \Exception("Tidak dapat memperpanjang kontrak. Terdapat {$tunggakan} tagihan yang belum lunas.");
            }

            // 3. Hitung tanggal mulai tagihan baru
            $tagihanTerakhir = $penyewaan->tagihan()
                ->orderByDesc('periode_tahun')
                ->orderByDesc('periode_bulan')
                ->first();

            $startPeriode = $tagihanTerakhir
                ? \Carbon\Carbon::create($tagihanTerakhir->periode_tahun, $tagihanTerakhir->periode_bulan)->addMonth()->startOfMonth()
                : \Carbon\Carbon::parse($penyewaan->tanggal_masuk)->startOfMonth();

            // 4. Update penyewaan
            $tanggalKeluarBaru = \Carbon\Carbon::parse($penyewaan->tanggal_keluar ?? $penyewaan->tanggal_masuk->addMonths($penyewaan->durasi_bulan))
                ->addMonths($tambahanBulan);
                
            $penyewaan->update([
                'durasi_bulan'    => $penyewaan->durasi_bulan + $tambahanBulan,
                'tanggal_keluar'  => $tanggalKeluarBaru,
                'perpanjangan_ke' => ($penyewaan->perpanjangan_ke ?? 0) + 1,
            ]);

            // 5. Generate tagihan baru untuk bulan tambahan
            \App\Services\InvoiceService::createInvoicesFromPeriode($penyewaan->fresh(), $startPeriode, $tambahanBulan);

            // 6. Kirim notifikasi ke penyewa
            \App\Models\Notifikasi::create([
                'user_id' => $penyewaan->user_id,
                'tipe' => \App\Enums\TipeNotifikasi::KontrakDiperpanjang,
                'judul' => 'Kontrak Kos Diperpanjang',
                'pesan' => "Kontrak Anda untuk kamar {$penyewaan->kamar->nama} telah diperpanjang selama {$tambahanBulan} bulan. Tagihan baru telah dibuat.",
                'read_at' => null,
            ]);

            // Kirim notifikasi ke admin (semua admin/staff)
            $admins = \App\Models\User::role(['admin', 'staff'])->get();
            foreach ($admins as $admin) {
                \App\Models\Notifikasi::create([
                    'user_id' => $admin->id,
                    'tipe' => \App\Enums\TipeNotifikasi::KontrakDiperpanjangAdmin,
                    'judul' => 'Perpanjangan Kontrak Baru',
                    'pesan' => "Penyewa {$penyewaan->user->name} memperpanjang kontrak kamar {$penyewaan->kamar->nama} selama {$tambahanBulan} bulan.",
                    'read_at' => null,
                ]);
            }

            return $penyewaan->fresh();
        });
    }
}
