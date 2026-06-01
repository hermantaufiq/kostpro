<?php

namespace App\Filament\Resources\Pembayarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PembayaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tagihan.id')
                    ->label('Tagihan'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('kode_pembayaran'),
                TextEntry::make('xendit_invoice_id')
                    ->placeholder('-'),
                TextEntry::make('xendit_external_id')
                    ->placeholder('-'),
                TextEntry::make('xendit_payment_url')
                    ->placeholder('-'),
                TextEntry::make('metode')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('channel_code')
                    ->placeholder('-'),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('biaya_admin')
                    ->numeric(),
                TextEntry::make('jumlah_diterima')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expired_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
