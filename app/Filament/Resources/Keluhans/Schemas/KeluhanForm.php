<?php

namespace App\Filament\Resources\Keluhans\Schemas;

use App\Enums\PrioritasKeluhan;
use App\Enums\StatusKeluhan;
use App\Models\Kamar;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KeluhanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('kamar_id')
                    ->relationship('kamar', 'nama')
                    ->searchable()
                    ->nullable()
                    ->placeholder('Tidak spesifik kamar'),
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Select::make('prioritas')
                    ->options(PrioritasKeluhan::class)
                    ->default(PrioritasKeluhan::Sedang)
                    ->required(),
                Textarea::make('deskripsi')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                FileUpload::make('foto_url')
                    ->label('Foto Bukti (Opsional)')
                    ->image()
                    ->directory('keluhan-foto')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(StatusKeluhan::class)
                    ->default(StatusKeluhan::Menunggu)
                    ->required(),
                Textarea::make('tanggapan_admin')
                    ->label('Tanggapan Admin')
                    ->placeholder('Isi tanggapan/respon untuk penyewa...')
                    ->rows(3)
                    ->columnSpanFull(),
                DateTimePicker::make('resolved_at')
                    ->label('Waktu Selesai')
                    ->nullable(),
            ]);
    }
}
