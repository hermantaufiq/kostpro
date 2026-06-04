<?php

namespace App\Filament\Resources\LayananTambahans;

use App\Filament\Resources\LayananTambahans\Pages\CreateLayananTambahan;
use App\Filament\Resources\LayananTambahans\Pages\EditLayananTambahan;
use App\Filament\Resources\LayananTambahans\Pages\ListLayananTambahans;
use App\Filament\Resources\LayananTambahans\Schemas\LayananTambahanForm;
use App\Filament\Resources\LayananTambahans\Tables\LayananTambahansTable;
use App\Models\LayananTambahan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LayananTambahanResource extends Resource
{
    protected static ?string $model = LayananTambahan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return LayananTambahanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LayananTambahansTable::configure($table);
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
            'index' => ListLayananTambahans::route('/'),
            'create' => CreateLayananTambahan::route('/create'),
            'edit' => EditLayananTambahan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
