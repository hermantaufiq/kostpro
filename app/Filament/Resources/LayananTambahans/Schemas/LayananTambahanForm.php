<?php

namespace App\Filament\Resources\LayananTambahans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LayananTambahanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Select::make('tipe_siklus')
                    ->options(['sekali' => 'Sekali', 'bulanan' => 'Bulanan'])
                    ->default('sekali')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
