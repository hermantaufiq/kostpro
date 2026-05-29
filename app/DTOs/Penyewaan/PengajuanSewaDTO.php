<?php

namespace App\DTOs\Penyewaan;

readonly class PengajuanSewaDTO
{
    public function __construct(
        public int $user_id,
        public int $kamar_id,
        public string $tanggal_masuk,
        public int $durasi_bulan,
        public ?string $catatan = null,
    ) {}
}
