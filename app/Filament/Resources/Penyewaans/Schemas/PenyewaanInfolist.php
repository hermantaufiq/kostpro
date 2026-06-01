<?php

namespace App\Filament\Resources\Penyewaans\Schemas;

use App\Models\Penyewaan;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PenyewaanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('kamar.id')
                    ->label('Kamar'),
                TextEntry::make('kode_penyewaan'),
                TextEntry::make('tanggal_masuk')
                    ->date(),
                TextEntry::make('tanggal_keluar')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('durasi_bulan')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('catatan_penyewa')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('catatan_admin')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tanggal_approval')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('ktp_url')
                    ->placeholder('-'),
                TextEntry::make('ktp_path')
                    ->placeholder('-'),
                TextEntry::make('kontrak_url')
                    ->placeholder('-'),
                TextEntry::make('harga_bulanan_snapshot')
                    ->numeric(),
                TextEntry::make('deposit_amount')
                    ->numeric(),
                IconEntry::make('deposit_paid')
                    ->boolean(),
                TextEntry::make('deposit_paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('checkin_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('checkout_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Penyewaan $record): bool => $record->trashed()),
            ]);
    }
}
