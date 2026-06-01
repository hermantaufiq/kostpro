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
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('judul')
                    ->required(),
                Textarea::make('pesan')
                    ->required()
                    ->columnSpanFull(),
                Select::make('tipe')
                    ->options(TipeNotifikasi::class)
                    ->default('sistem')
                    ->required(),
                TextInput::make('data'),
                TextInput::make('action_url')
                    ->url(),
                DateTimePicker::make('read_at'),
            ]);
    }
}
