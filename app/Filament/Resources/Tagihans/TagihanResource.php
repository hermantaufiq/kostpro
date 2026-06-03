<?php

namespace App\Filament\Resources\Tagihans;

use App\Filament\Resources\Tagihans\Pages\CreateTagihan;
use App\Filament\Resources\Tagihans\Pages\EditTagihan;
use App\Filament\Resources\Tagihans\Pages\ListTagihans;
use App\Filament\Resources\Tagihans\Pages\ViewTagihan;
use App\Filament\Resources\Tagihans\Schemas\TagihanForm;
use App\Filament\Resources\Tagihans\Schemas\TagihanInfolist;
use App\Filament\Resources\Tagihans\Tables\TagihansTable;
use App\Models\Tagihan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;
    protected static ?string $recordTitleAttribute = 'no_tagihan';

    public static function getNavigationIcon(): string { return 'heroicon-o-document-text'; }
    public static function getNavigationLabel(): string { return 'Tagihan'; }
    public static function getNavigationGroup(): ?string { return 'Keuangan'; }
    public static function getNavigationSort(): ?int { return 1; }
    public static function getModelLabel(): string { return 'Tagihan'; }

    public static function form(Schema $schema): Schema { return TagihanForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return TagihanInfolist::configure($schema); }
    public static function table(Table $table): Table { return TagihansTable::configure($table); }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => ListTagihans::route('/'),
            'create' => CreateTagihan::route('/create'),
            'view'   => ViewTagihan::route('/{record}'),
            'edit'   => EditTagihan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
