<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TenantResource\Pages;
use App\Filament\Admin\Resources\TenantResource\RelationManagers\PenyewaanRelationManager;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Penyewa')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->unique(User::class, 'email', ignoreRecord: true),
                TextInput::make('phone')->tel(),
                TextInput::make('nik')->maxLength(20)->unique(User::class, 'nik', ignoreRecord: true),
                Textarea::make('alamat')->rows(3),
            ]),

            Section::make('Dokumen')->schema([
                FileUpload::make('foto_ktp_url')->label('KTP')->image()->directory('ktp'),
            ]),

            Section::make('Status')->schema([
                Checkbox::make('is_active')->label('Aktif'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('nik'),
                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors(['success' => fn($state) => $state, 'danger' => fn($state) => !$state])
                    ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Tidak Aktif'),
            ])
            ->filters([SelectFilter::make('is_active')])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_type', 'tenant');
    }

    public static function getNavigationIcon(): ?string { return 'heroicon-o-users'; }
    public static function getNavigationLabel(): string { return 'Penyewa'; }
    public static function getNavigationGroup(): ?string { return 'Master Data'; }
    public static function getNavigationSort(): ?int { return 2; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            PenyewaanRelationManager::class,
        ];
    }
}
