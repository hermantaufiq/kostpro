<?php

namespace App\Filament\Admin\Actions;

use App\Models\Penyewaan;
use App\Enums\StatusPenyewaan;
use Filament\Actions\Action;

class RejectPenyewaanAction
{
    public static function make(): Action
    {
        return Action::make('reject')
            ->label('Tolak Pengajuan')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->action(fn(Penyewaan $record) => $record->update(['status' => StatusPenyewaan::Rejected]))
            ->successNotificationTitle('Pengajuan ditolak');
    }
}
