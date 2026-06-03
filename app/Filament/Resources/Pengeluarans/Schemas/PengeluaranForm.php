<?php

namespace App\Filament\Resources\Pengeluarans\Schemas;

use App\Enums\KategoriPengeluaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PengeluaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori')
                    ->options(KategoriPengeluaran::class)
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                Textarea::make('keterangan')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('bukti_url')
                    ->url(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
