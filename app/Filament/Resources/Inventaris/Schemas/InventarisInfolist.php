<?php

namespace App\Filament\Resources\Inventaris\Schemas;

use App\Models\Inventaris;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventarisInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Barang')
                    ->schema([
                        TextEntry::make('nama_barang')->label('Nama Barang')->weight('bold'),
                        TextEntry::make('kode_barang')->label('Kode Barang')->placeholder('-')->copyable(),
                        TextEntry::make('kamar.nama')->label('Lokasi Kamar')->placeholder('Fasilitas Umum'),
                        TextEntry::make('kondisi')->label('Kondisi')->badge(),
                        TextEntry::make('harga_beli')->label('Harga Beli')->money('idr')->placeholder('-'),
                        TextEntry::make('tanggal_beli')->label('Tanggal Beli')->date()->placeholder('-'),
                        TextEntry::make('keterangan')->label('Keterangan')->columnSpanFull()->placeholder('-'),
                        ImageEntry::make('foto_url')->label('Foto Barang')->columnSpanFull()->placeholder('-'),
                    ])
                    ->columns(2),
                Section::make('Waktu')
                    ->schema([
                        TextEntry::make('created_at')->label('Dibuat Pada')->dateTime(),
                        TextEntry::make('updated_at')->label('Diperbarui Pada')->dateTime(),
                        TextEntry::make('deleted_at')->label('Dihapus Pada')->dateTime()
                            ->visible(fn (Inventaris $record): bool => $record->trashed()),
                    ])
                    ->collapsed(),
            ]);
    }
}
