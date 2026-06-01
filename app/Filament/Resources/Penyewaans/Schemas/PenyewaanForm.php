<?php

namespace App\Filament\Resources\Penyewaans\Schemas;

use App\Enums\StatusPenyewaan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PenyewaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('Informasi Penyewa & Kamar')
                    ->schema([
                        Select::make('user_id')
                            ->label('Penyewa')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('kamar_id')
                            ->label('Kamar')
                            ->relationship('kamar', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('kode_penyewaan')
                            ->label('Kode Booking')
                            ->default(fn () => 'BOOK-' . strtoupper(uniqid()))
                            ->readOnly()
                            ->required(),
                        Select::make('status')
                            ->options(StatusPenyewaan::class)
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Durasi Sewa')
                    ->schema([
                        DatePicker::make('tanggal_masuk')
                            ->required(),
                        DatePicker::make('tanggal_keluar'),
                        TextInput::make('durasi_bulan')
                            ->required()
                            ->numeric()
                            ->default(1),
                    ])->columns(3),

                \Filament\Forms\Components\Section::make('Biaya & Tagihan')
                    ->schema([
                        TextInput::make('harga_bulanan_snapshot')
                            ->label('Harga Bulanan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('deposit_amount')
                            ->label('Deposit')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Toggle::make('deposit_paid')
                            ->label('Deposit Lunas?'),
                    ])->columns(3),

                \Filament\Forms\Components\Section::make('Dokumen & Catatan')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('ktp_path')
                            ->label('KTP')
                            ->image()
                            ->directory('penyewaan-ktp'),
                        \Filament\Forms\Components\FileUpload::make('kontrak_url')
                            ->label('Surat Kontrak (PDF/Image)')
                            ->directory('penyewaan-kontrak'),
                        Textarea::make('catatan_penyewa')
                            ->columnSpanFull(),
                        Textarea::make('catatan_admin')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
