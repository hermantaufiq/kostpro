<?php

namespace App\Filament\Resources\Keluhans\Schemas;

use App\Enums\PrioritasKeluhan;
use App\Enums\StatusKeluhan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KeluhanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('kamar_id')
                    ->relationship('kamar', 'id'),
                TextInput::make('judul')
                    ->required(),
                Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(StatusKeluhan::class)
                    ->default('menunggu')
                    ->required(),
                Select::make('prioritas')
                    ->options(PrioritasKeluhan::class)
                    ->default('sedang')
                    ->required(),
                TextInput::make('foto_url')
                    ->url(),
                Textarea::make('tanggapan_admin')
                    ->columnSpanFull(),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
