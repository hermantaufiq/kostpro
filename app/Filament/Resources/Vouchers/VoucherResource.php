<?php

namespace App\Filament\Resources\Vouchers;

use App\Filament\Resources\Vouchers\Pages\CreateVoucher;
use App\Filament\Resources\Vouchers\Pages\EditVoucher;
use App\Filament\Resources\Vouchers\Pages\ListVouchers;
use App\Filament\Resources\Vouchers\Pages\ViewVoucher;
use App\Filament\Resources\Vouchers\Schemas\VoucherForm;
use App\Filament\Resources\Vouchers\Schemas\VoucherInfolist;
use App\Filament\Resources\Vouchers\Tables\VouchersTable;
use App\Models\Voucher;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $recordTitleAttribute = 'kode_voucher';

    public static function getNavigationIcon(): string { return 'heroicon-o-ticket'; }
    public static function getNavigationLabel(): string { return 'Voucher Diskon'; }
    public static function getNavigationGroup(): ?string { return 'Keuangan'; }
    public static function getNavigationSort(): ?int { return 3; }
    public static function getModelLabel(): string { return 'Voucher'; }
    public static function getPluralModelLabel(): string { return 'Voucher Diskon'; }

    public static function getNavigationBadge(): ?string
    {
        return (string) Voucher::aktif()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema { return VoucherForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return VoucherInfolist::configure($schema); }
    public static function table(Table $table): Table { return VouchersTable::configure($table); }

    public static function getRelations(): array { return []; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'tagihan', 'pembayaran', 'pembayaranSumber']);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVouchers::route('/'),
            'create' => CreateVoucher::route('/create'),
            'view'   => ViewVoucher::route('/{record}'),
            'edit'   => EditVoucher::route('/{record}/edit'),
        ];
    }
}
