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
                TextInput::make('kode_kamar')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                Select::make('tipe')
                    ->options(TipeKamar::class)
                    ->default('standar')
                    ->required(),
                TextInput::make('lantai')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('luas'),
                TextInput::make('harga_bulanan')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_deposit')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('fasilitas'),
                Select::make('status')
                    ->options(StatusKamar::class)
                    ->default('tersedia')
                    ->required(),
                TextInput::make('meta'),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}
