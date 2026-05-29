<?php

namespace App\Services\Tagihan;

use App\Contracts\Services\TagihanServiceInterface;
use App\Contracts\Repositories\TagihanRepositoryInterface;
use App\Enums\StatusTagihan;

class TagihanService implements TagihanServiceInterface
{
    public function __construct(
        private TagihanRepositoryInterface $tagihanRepository
    ) {}

    public function generateTagihanBulanan()
    {
        // Implementation for scheduled task
    }

    public function markAsPaid($id)
    {
        return $this->tagihanRepository->update($id, [
            'status' => StatusTagihan::Paid,
            'tanggal_bayar' => now(),
        ]);
    }

    public function checkOverdue()
    {
        $unpaid = $this->tagihanRepository->findUnpaid();
        
        foreach ($unpaid as $tagihan) {
            if ($tagihan->tanggal_jatuh_tempo < now()->startOfDay()) {
                $denda_persen = config('app.kostpro_denda_persen', 5);
                $denda = ($tagihan->jumlah_tagihan * $denda_persen) / 100;
                
                $this->tagihanRepository->update($tagihan->id, [
                    'status' => StatusTagihan::Overdue,
                    'jumlah_denda' => $denda,
                ]);
            }
        }
    }
}
