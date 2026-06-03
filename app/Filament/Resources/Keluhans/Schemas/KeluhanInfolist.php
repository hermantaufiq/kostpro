<?php

namespace App\Filament\Resources\Keluhans\Schemas;

use App\Models\Keluhan;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KeluhanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Keluhan')
                    ->schema([
                        TextEntry::make('user.name')->label('Penyewa'),
                        TextEntry::make('kamar.nama')->label('Kamar')->placeholder('-'),
                        TextEntry::make('judul')->label('Judul'),
                        TextEntry::make('prioritas')->label('Prioritas')->badge(),
                        TextEntry::make('status')->label('Status')->badge(),
                        TextEntry::make('created_at')->label('Dilaporkan')->dateTime(),
                        TextEntry::make('deskripsi')->label('Deskripsi')->columnSpanFull(),
                        ImageEntry::make('foto_url')->label('Foto')->placeholder('-')->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Tanggapan Admin')
                    ->schema([
                        TextEntry::make('tanggapan_admin')->label('Tanggapan')->placeholder('Belum ada tanggapan')->columnSpanFull(),
                        TextEntry::make('resolved_at')->label('Waktu Selesai')->dateTime()->placeholder('-'),
                    ]),
            ]);
    }
}
