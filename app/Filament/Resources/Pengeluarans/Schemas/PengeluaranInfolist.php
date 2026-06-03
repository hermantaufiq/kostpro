<?php

namespace App\Filament\Resources\Pengeluarans\Schemas;

use App\Models\Pengeluaran;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PengeluaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pengeluaran')
                    ->schema([
                        TextEntry::make('tanggal')->label('Tanggal')->date(),
                        TextEntry::make('kategori')->label('Kategori')->badge(),
                        TextEntry::make('jumlah')->label('Jumlah')->money('idr'),
                        TextEntry::make('creator.name')->label('Dibuat Oleh'),
                        TextEntry::make('keterangan')->label('Keterangan')->columnSpanFull(),
                        ImageEntry::make('bukti_url')->label('Bukti Nota')->placeholder('-')->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Waktu')
                    ->schema([
                        TextEntry::make('created_at')->label('Dibuat Pada')->dateTime(),
                        TextEntry::make('updated_at')->label('Diperbarui')->dateTime(),
                    ])
                    ->collapsed(),
            ]);
    }
}
