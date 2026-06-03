<?php

namespace App\Filament\Resources\Pembayarans;

use App\Filament\Resources\Pembayarans\Pages\CreatePembayaran;
use App\Filament\Resources\Pembayarans\Pages\EditPembayaran;
use App\Filament\Resources\Pembayarans\Pages\ListPembayarans;
use App\Filament\Resources\Pembayarans\Pages\ViewPembayaran;
use App\Filament\Resources\Pembayarans\Schemas\PembayaranForm;
use App\Filament\Resources\Pembayarans\Schemas\PembayaranInfolist;
use App\Filament\Resources\Pembayarans\Tables\PembayaransTable;
use App\Models\Pembayaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    public static function getNavigationIcon(): string { return 'heroicon-o-credit-card'; }
    public static function getNavigationLabel(): string { return 'Pembayaran'; }
    public static function getNavigationGroup(): ?string { return 'Keuangan'; }
    public static function getNavigationSort(): ?int { return 2; }
    public static function getModelLabel(): string { return 'Pembayaran'; }

    public static function form(Schema $schema): Schema { return PembayaranForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return PembayaranInfolist::configure($schema); }
    public static function table(Table $table): Table { return PembayaransTable::configure($table); }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListPembayarans::route('/'),
            'create' => CreatePembayaran::route('/create'),
            'view'   => ViewPembayaran::route('/{record}'),
            'edit'   => EditPembayaran::route('/{record}/edit'),
        ];
    }
}
