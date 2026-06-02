<?php

namespace App\Filament\Admin\Actions;

use App\Models\Pembayaran;
use App\Enums\StatusPembayaran;
use Filament\Actions\Action;

class ConfirmPaymentAction
{
    public static function make(): Action
    {
        return Action::make('confirm')
            ->label('Konfirmasi Pembayaran')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->action(function (Pembayaran $record) {
                $record->update(['status' => StatusPembayaran::Lunas, 'tanggal_pembayaran' => now()]);
                $record->tagihan->update(['status' => 'Lunas']);
            })
            ->successNotificationTitle('Pembayaran dikonfirmasi & tagihan diupdate');
    }
}
