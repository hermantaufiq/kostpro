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
                \Filament\Schemas\Components\Section::make('Informasi Relasi')
                    ->schema([
                        Select::make('penyewaan_id')
                            ->label('Kode Booking')
                            ->relationship('penyewaan', 'kode_penyewaan')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('user_id')
                            ->label('Penyewa')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('kode_tagihan')
                            ->label('No. Invoice')
                            ->default(fn () => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()))
                            ->readOnly()
                            ->required(),
                        Select::make('status')
                            ->options(StatusTagihan::class)
                            ->default('unpaid')
                            ->required(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Rincian Biaya')
                    ->schema([
                        TextInput::make('jumlah_tagihan')
                            ->label('Tagihan Pokok')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('jumlah_denda')
                            ->label('Denda (Keterlambatan)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        TextInput::make('total_tagihan')
                            ->label('Total Harus Dibayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Dihitung otomatis oleh MySQL (tagihan pokok + denda).'),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Rincian Biaya Tambahan')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('nama_item')
                                    ->label('Nama Item (Listrik, Air, dll)')
                                    ->required(),
                                TextInput::make('nominal')
                                    ->label('Nominal')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Biaya'),
                    ]),

                \Filament\Schemas\Components\Section::make('Periode & Waktu')
                    ->schema([
                        TextInput::make('periode_bulan')
                            ->label('Bulan ke-')
                            ->required()
                            ->numeric(),
                        TextInput::make('periode_tahun')
                            ->label('Tahun')
                            ->required()
                            ->numeric()
                            ->default(date('Y')),
                        DatePicker::make('tanggal_tagihan')
                            ->label('Tanggal Diterbitkan')
                            ->default(now())
                            ->required(),
                        DatePicker::make('tanggal_jatuh_tempo')
                            ->label('Jatuh Tempo')
                            ->required(),
                        DateTimePicker::make('tanggal_bayar')
                            ->label('Waktu Pembayaran'),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Catatan')
                    ->schema([
                        Textarea::make('catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
