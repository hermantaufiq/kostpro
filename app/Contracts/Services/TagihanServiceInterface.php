<?php

namespace App\Contracts\Services;

interface TagihanServiceInterface
{
    public function generateTagihanBulanan();
    public function markAsPaid($id);
    public function checkOverdue();
}
