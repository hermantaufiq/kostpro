<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use App\Models\Tagihan;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TagihanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('penyewaan.id')
                    ->label('Penyewaan'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('kode_tagihan'),
                TextEntry::make('periode_bulan')
                    ->numeric(),
                TextEntry::make('periode_tahun')
                    ->numeric(),
                TextEntry::make('jumlah_tagihan')
                    ->numeric(),
                TextEntry::make('jumlah_denda')
                    ->numeric(),
                TextEntry::make('total_tagihan')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('tanggal_tagihan')
                    ->date(),
                TextEntry::make('tanggal_jatuh_tempo')
                    ->date(),
                TextEntry::make('tanggal_bayar')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('reminder_count')
                    ->numeric(),
                TextEntry::make('last_reminder_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_auto_generated')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Tagihan $record): bool => $record->trashed()),
            ]);
    }
}
