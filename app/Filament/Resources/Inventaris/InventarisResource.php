<?php

namespace App\Filament\Resources\Inventaris;

use App\Filament\Resources\Inventaris\Pages\CreateInventaris;
use App\Filament\Resources\Inventaris\Pages\EditInventaris;
use App\Filament\Resources\Inventaris\Pages\ListInventaris;
use App\Filament\Resources\Inventaris\Pages\ViewInventaris;
use App\Filament\Resources\Inventaris\Schemas\InventarisForm;
use App\Filament\Resources\Inventaris\Schemas\InventarisInfolist;
use App\Filament\Resources\Inventaris\Tables\InventarisTable;
use App\Models\Inventaris;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarisResource extends Resource
{
    protected static ?string $model = Inventaris::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;
    protected static ?string $recordTitleAttribute = 'nama_barang';

    public static function getNavigationLabel(): string
    {
        return 'Inventaris';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function form(Schema $schema): Schema
    {
        return InventarisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InventarisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventarisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventaris::route('/'),
            'create' => CreateInventaris::route('/create'),
            'view' => ViewInventaris::route('/{record}'),
            'edit' => EditInventaris::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
