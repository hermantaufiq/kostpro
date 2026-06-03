<?php

namespace App\Filament\Resources\Inventaris\Schemas;

use App\Enums\KondisiInventaris;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventarisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_barang')
                    ->required()
                    ->maxLength(255),
                TextInput::make('kode_barang')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('kamar_id')
                    ->relationship('kamar', 'nama')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->label('Kamar (Opsional)'),
                Select::make('kondisi')
                    ->options(KondisiInventaris::class)
                    ->default(KondisiInventaris::Baik)
                    ->required(),
                TextInput::make('harga_beli')
                    ->numeric()
                    ->prefix('Rp')
                    ->maxValue(999999999999.99),
                DatePicker::make('tanggal_beli')
                    ->maxDate(now()),
                Textarea::make('keterangan')
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('foto_url')
                    ->label('Foto Barang')
                    ->image()
                    ->directory('inventaris-foto')
                    ->columnSpanFull(),
            ]);
    }
}
