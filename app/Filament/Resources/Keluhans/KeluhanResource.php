<?php

namespace App\Filament\Resources\Keluhans;

use App\Filament\Resources\Keluhans\Pages\CreateKeluhan;
use App\Filament\Resources\Keluhans\Pages\EditKeluhan;
use App\Filament\Resources\Keluhans\Pages\ListKeluhans;
use App\Filament\Resources\Keluhans\Pages\ViewKeluhan;
use App\Filament\Resources\Keluhans\Schemas\KeluhanForm;
use App\Filament\Resources\Keluhans\Schemas\KeluhanInfolist;
use App\Filament\Resources\Keluhans\Tables\KeluhansTable;
use App\Models\Keluhan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeluhanResource extends Resource
{
    protected static ?string $model = Keluhan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;
    protected static ?string $recordTitleAttribute = 'judul';

    public static function getNavigationLabel(): string
    {
        return 'Keluhan Penyewa';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'menunggu')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return KeluhanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KeluhanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KeluhansTable::configure($table);
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
            'index' => ListKeluhans::route('/'),
            'create' => CreateKeluhan::route('/create'),
            'view' => ViewKeluhan::route('/{record}'),
            'edit' => EditKeluhan::route('/{record}/edit'),
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
