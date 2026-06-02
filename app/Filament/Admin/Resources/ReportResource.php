<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Models\AuditLog;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $recordTitleAttribute = 'event';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Laporan Audit')->schema([
                Select::make('user_id')->relationship('user', 'name')->disabled(),
                Select::make('event')->disabled(),
                DatePicker::make('created_at')->disabled(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->searchable(),
                TextColumn::make('event')->badge(),
                TextColumn::make('model_type')->label('Model'),
                TextColumn::make('description')->limit(50),
                TextColumn::make('created_at')->datetime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('event'),
                Filter::make('tanggal_mulai')
                    ->form([DatePicker::make('tanggal_dari')])
                    ->query(fn (Builder $query, array $data) => $query->when($data['tanggal_dari'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))),
                Filter::make('tanggal_akhir')
                    ->form([DatePicker::make('tanggal_sampai')])
                    ->query(fn (Builder $query, array $data) => $query->when($data['tanggal_sampai'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getNavigationIcon(): ?string { return 'heroicon-o-chart-bar'; }
    public static function getNavigationLabel(): string { return 'Laporan'; }
    public static function getNavigationGroup(): ?string { return 'Sistem'; }
    public static function getNavigationSort(): ?int { return 9; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}
