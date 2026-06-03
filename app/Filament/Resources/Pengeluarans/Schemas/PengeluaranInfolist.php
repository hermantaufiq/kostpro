<?php

namespace App\Filament\Resources\Pengeluarans\Schemas;

use App\Models\Pengeluaran;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PengeluaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kategori')
                    ->badge(),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('keterangan')
                    ->columnSpanFull(),
                TextEntry::make('bukti_url')
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Pengeluaran $record): bool => $record->trashed()),
            ]);
    }
}
