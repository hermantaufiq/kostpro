<?php

namespace App\Filament\Admin\Actions;

use App\Models\Penyewaan;
use App\Models\Tagihan;
use App\Enums\StatusPenyewaan;
use Filament\Actions\Action;

class ApprovePenyewaanAction
{
    public static function make(): Action
    {
        return Action::make('approve')
            ->label('Setujui Pengajuan')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->action(function (Penyewaan $record) {
                $record->update(['status' => StatusPenyewaan::Approved]);

                // Create invoices for each month
                $startDate = $record->tanggal_masuk;
                $monthCount = $record->durasi_bulan ?? 1;

                for ($i = 0; $i < $monthCount; $i++) {
                    $dueDate = $startDate->copy()->addMonths($i)->endOfMonth();
                    Tagihan::create([
                        'penyewaan_id' => $record->id,
                        'nominal' => $record->harga_bulanan_snapshot,
                        'denda' => 0,
                        'tanggal_jatuh_tempo' => $dueDate,
                        'status' => 'Pending',
                    ]);
                }
            })
            ->successNotificationTitle('Pengajuan disetujui & tagihan dibuat otomatis');
    }
}
