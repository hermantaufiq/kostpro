<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use App\Enums\StatusTagihan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TagihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('penyewaan_id')
                    ->relationship('penyewaan', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('kode_tagihan')
                    ->required(),
                TextInput::make('periode_bulan')
                    ->required()
                    ->numeric(),
                TextInput::make('periode_tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('jumlah_tagihan')
                    ->required()
                    ->numeric(),
                TextInput::make('jumlah_denda')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_tagihan')
                    ->numeric(),
                Select::make('status')
                    ->options(StatusTagihan::class)
                    ->default('unpaid')
                    ->required(),
                DatePicker::make('tanggal_tagihan')
                    ->required(),
                DatePicker::make('tanggal_jatuh_tempo')
                    ->required(),
                DateTimePicker::make('tanggal_bayar'),
                TextInput::make('reminder_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('last_reminder_at'),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                Toggle::make('is_auto_generated')
                    ->required(),
            ]);
    }
}
