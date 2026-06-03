<?php

namespace App\Filament\Resources\Kamars;

use App\Filament\Resources\Kamars\Pages\CreateKamar;
use App\Filament\Resources\Kamars\Pages\EditKamar;
use App\Filament\Resources\Kamars\Pages\ListKamars;
use App\Filament\Resources\Kamars\Pages\ViewKamar;
use App\Filament\Resources\Kamars\Schemas\KamarForm;
use App\Filament\Resources\Kamars\Schemas\KamarInfolist;
use App\Filament\Resources\Kamars\Tables\KamarsTable;
use App\Models\Kamar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;
    protected static ?string $recordTitleAttribute = 'nama';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getNavigationLabel(): string
    {
        return 'Data Kamar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Properti';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getModelLabel(): string
    {
        return 'Kamar';
    }
    public static function form(Schema $schema): Schema
    {
        return KamarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KamarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KamarsTable::configure($table);
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
            'index' => ListKamars::route('/'),
            'create' => CreateKamar::route('/create'),
            'view' => ViewKamar::route('/{record}'),
            'edit' => EditKamar::route('/{record}/edit'),
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
