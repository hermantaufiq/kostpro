<?php

namespace App\Filament\Admin\Actions;

use App\Models\Pembayaran;
use App\Enums\StatusPembayaran;
use Filament\Actions\Action;

class RejectPaymentAction
{
    public static function make(): Action
    {
        return Action::make('reject')
            ->label('Tolak Pembayaran')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->action(fn(Pembayaran $record) => $record->update(['status' => StatusPembayaran::Gagal]))
            ->successNotificationTitle('Pembayaran ditolak');
    }
}
