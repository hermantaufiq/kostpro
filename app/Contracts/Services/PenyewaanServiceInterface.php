<?php

namespace App\Contracts\Services;

use App\DTOs\Penyewaan\PengajuanSewaDTO;

interface PenyewaanServiceInterface
{
    public function ajukanSewa(PengajuanSewaDTO $dto);
    public function approveSewa($id, $adminId);
    public function rejectSewa($id, $adminId, $catatan = null);
    public function activateSewa($id);
    public function perpanjangKontrak($penyewaanId, int $tambahanBulan, $adminId = null);
}
