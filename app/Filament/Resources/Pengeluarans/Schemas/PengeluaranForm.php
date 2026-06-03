<?php

namespace App\Filament\Resources\Pengeluarans\Schemas;

use App\Enums\KategoriPengeluaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                    ->numeric()
                    ->prefix('Rp'),
                DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                Textarea::make('keterangan')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('bukti_url')
                    ->label('Bukti Nota')
                    ->image()
                    ->directory('pengeluaran-bukti')
                    ->columnSpanFull(),
                Hidden::make('created_by')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
