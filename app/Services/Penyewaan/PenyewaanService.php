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
}
