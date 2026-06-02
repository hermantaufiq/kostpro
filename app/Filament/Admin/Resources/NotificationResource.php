<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NotificationResource\Pages;
use App\Models\Notifikasi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Enums\TipeNotifikasi;

class NotificationResource extends Resource
{
    protected static ?string $model = Notifikasi::class;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Notifikasi')->schema([
                TextInput::make('judul')->required(),
                Textarea::make('pesan')->required()->rows(4),
                Select::make('tipe')
                    ->options(fn() => collect(TipeNotifikasi::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()]))
                    ->required(),
                Select::make('user_id')->relationship('user', 'name'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Penerima'),
                TextColumn::make('tipe')
                    ->formatStateUsing(fn($state) => $state instanceof TipeNotifikasi ? $state->label() : $state),
                TextColumn::make('read_at')
                    ->label('Status')
                    ->badge()
                    ->colors(['success' => fn($state) => $state !== null, 'warning' => fn($state) => $state === null])
                    ->formatStateUsing(fn($state) => $state !== null ? 'Dibaca' : 'Belum Dibaca'),
                TextColumn::make('created_at')->datetime('d/m/Y H:i')->sortable(),
            ])
            ->filters([SelectFilter::make('tipe')])
            ->defaultSort('created_at', 'desc');
    }

    public static function getNavigationIcon(): ?string { return 'heroicon-o-bell'; }
    public static function getNavigationLabel(): string { return 'Notifikasi'; }
    public static function getNavigationGroup(): ?string { return 'Sistem'; }
    public static function getNavigationSort(): ?int { return 8; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
