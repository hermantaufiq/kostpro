<?php

namespace App\Filament\Resources\Kamars\Schemas;

use App\Enums\StatusKamar;
use App\Enums\TipeKamar;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KamarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('kode_kamar')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('nama')
                            ->required(),
                        Select::make('tipe')
                            ->options(TipeKamar::class)
                            ->default('standar')
                            ->required(),
                        Select::make('status')
                            ->options(StatusKamar::class)
                            ->default('tersedia')
                            ->required(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Detail Ruangan & Fasilitas')
                    ->schema([
                        TextInput::make('lantai')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('luas')
                            ->placeholder('Contoh: 4x5'),
                        \Filament\Forms\Components\TagsInput::make('fasilitas')
                            ->columnSpanFull()
                            ->placeholder('Tambah fasilitas lalu tekan enter')
                            ->suggestions(['AC', 'Kasur Springbed', 'Lemari', 'Kamar Mandi Dalam', 'Water Heater', 'Meja Belajar', 'WiFi']),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Harga')
                    ->schema([
                        TextInput::make('harga_bulanan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('harga_deposit')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Media & Deskripsi')
                    ->schema([
                        \Filament\Forms\Components\RichEditor::make('deskripsi')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->directory('kamar-images')
                            ->columnSpanFull(),
                        Toggle::make('is_featured')
                            ->label('Tampilkan di halaman utama')
                            ->default(false),
                    ]),
            ]);
    }
}
