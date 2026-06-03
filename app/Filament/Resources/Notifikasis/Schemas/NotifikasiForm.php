<?php

namespace App\Filament\Resources\Notifikasis\Schemas;

use App\Enums\TipeNotifikasi;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NotifikasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Tujuan & Tipe')
                    ->schema([
                        Select::make('user_id')
                            ->label('Kirim ke (Penyewa)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('tipe')
                            ->label('Tipe Notifikasi')
                            ->options(TipeNotifikasi::class)
                            ->default('sistem')
                            ->required(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Isi Notifikasi')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul / Subjek')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('pesan')
                            ->label('Isi Pesan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('action_url')
                            ->label('URL Tombol Aksi (opsional)')
                            ->url()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
