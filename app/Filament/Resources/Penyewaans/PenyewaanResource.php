<?php

namespace App\Filament\Resources\Penyewaans;

use App\Filament\Resources\Penyewaans\Pages\CreatePenyewaan;
use App\Filament\Resources\Penyewaans\Pages\EditPenyewaan;
use App\Filament\Resources\Penyewaans\Pages\ListPenyewaans;
use App\Filament\Resources\Penyewaans\Pages\ViewPenyewaan;
use App\Filament\Resources\Penyewaans\Schemas\PenyewaanForm;
use App\Filament\Resources\Penyewaans\Schemas\PenyewaanInfolist;
use App\Filament\Resources\Penyewaans\Tables\PenyewaansTable;
use App\Models\Penyewaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenyewaanResource extends Resource
{
    protected static ?string $model = Penyewaan::class;
    protected static ?string $recordTitleAttribute = 'kode_penyewaan';

    public static function getNavigationIcon(): string { return 'heroicon-o-clipboard-document-list'; }
    public static function getNavigationLabel(): string { return 'Data Penyewaan'; }
    public static function getNavigationGroup(): ?string { return 'Manajemen Properti'; }
    public static function getNavigationSort(): ?int { return 2; }
    public static function getModelLabel(): string { return 'Penyewaan'; }

    public static function form(Schema $schema): Schema { return PenyewaanForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return PenyewaanInfolist::configure($schema); }
    public static function table(Table $table): Table { return PenyewaansTable::configure($table); }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListPenyewaans::route('/'),
            'create' => CreatePenyewaan::route('/create'),
            'view'   => ViewPenyewaan::route('/{record}'),
            'edit'   => EditPenyewaan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
