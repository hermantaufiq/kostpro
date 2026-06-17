<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use App\Enums\StatusVoucher;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Voucher')
                    ->description('Admin dapat menerbitkan voucher manual untuk penyewa tertentu.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Penyewa')
                            ->relationship(
                                'user',
                                'name',
                                fn ($query) => $query->where('user_type', 'tenant')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('kode_voucher')
                            ->label('Kode Voucher')
                            ->placeholder('Kosongkan untuk generate otomatis')
                            ->unique(ignoreRecord: true)
                            ->maxLength(30)
                            ->helperText('Format otomatis: VCH-YYYYMMDD-XXXX'),
                        TextInput::make('nominal_diskon')
                            ->label('Nominal Diskon (Rp)')
                            ->numeric()
                            ->required()
                            ->minValue(1000)
                            ->default(config('app.kostpro_voucher_nominal', 10000))
                            ->prefix('Rp'),
                        Select::make('status')
                            ->label('Status')
                            ->options(StatusVoucher::class)
                            ->default(StatusVoucher::Aktif)
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(),
                        Select::make('sumber')
                            ->label('Sumber')
                            ->options([
                                'manual_admin'           => 'Manual Admin',
                                'pembayaran_tepat_waktu' => 'Bayar Tepat Waktu',
                                'streak_tepat_waktu'     => 'Streak 3× Tepat Waktu',
                            ])
                            ->default('manual_admin')
                            ->required(),
                        DateTimePicker::make('berlaku_sampai')
                            ->label('Berlaku Sampai')
                            ->required()
                            ->default(now()->addDays((int) config('app.kostpro_voucher_berlaku_hari', 30)))
                            ->minDate(now()),
                    ])->columns(2),
            ]);
    }
}
