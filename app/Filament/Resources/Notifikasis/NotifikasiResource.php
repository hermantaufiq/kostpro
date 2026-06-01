<?php

namespace App\Filament\Resources\Notifikasis;

use App\Filament\Resources\Notifikasis\Pages\CreateNotifikasi;
use App\Filament\Resources\Notifikasis\Pages\EditNotifikasi;
use App\Filament\Resources\Notifikasis\Pages\ListNotifikasis;
use App\Filament\Resources\Notifikasis\Pages\ViewNotifikasi;
use App\Filament\Resources\Notifikasis\Schemas\NotifikasiForm;
use App\Filament\Resources\Notifikasis\Schemas\NotifikasiInfolist;
use App\Filament\Resources\Notifikasis\Tables\NotifikasisTable;
use App\Models\Notifikasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotifikasiResource extends Resource
{
    protected static ?string $model = Notifikasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return NotifikasiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NotifikasiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotifikasisTable::configure($table);
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
            'index' => ListNotifikasis::route('/'),
            'create' => CreateNotifikasi::route('/create'),
            'view' => ViewNotifikasi::route('/{record}'),
            'edit' => EditNotifikasi::route('/{record}/edit'),
        ];
    }
}
