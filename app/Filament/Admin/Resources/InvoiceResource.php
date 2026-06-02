<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InvoiceResource\Pages;
use App\Filament\Admin\Resources\InvoiceResource\RelationManagers\PembayaranRelationManager;
use App\Models\Tagihan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Enums\StatusTagihan;

class InvoiceResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $recordTitleAttribute = 'kode_tagihan';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Tagihan')->schema([
                TextInput::make('kode_tagihan')->disabled(),
                Select::make('penyewaan_id')->relationship('penyewaan', 'kode_penyewaan')->required(),
                TextInput::make('nominal')->numeric()->required(),
                TextInput::make('denda')->numeric(),
                DatePicker::make('tanggal_jatuh_tempo')->required(),
                DatePicker::make('tanggal_dibayar'),
            ]),

            Section::make('Status & Catatan')->schema([
                Select::make('status')
                    ->options(fn() => collect(StatusTagihan::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()]))
                    ->required(),
                Textarea::make('catatan')->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_tagihan')->searchable()->sortable(),
                TextColumn::make('penyewaan.user.name')->label('Penyewa'),
                TextColumn::make('nominal')->money('IDR', 0),
                TextColumn::make('tanggal_jatuh_tempo')->date('d/m/Y'),
                BadgeColumn::make('status')
                    ->formatStateUsing(fn($state) => $state instanceof StatusTagihan ? $state->label() : $state)
                    ->colors([
                        'success' => fn($s) => $s instanceof StatusTagihan && $s === StatusTagihan::Lunas,
                        'danger' => fn($s) => $s instanceof StatusTagihan && ($s === StatusTagihan::JatuhTempo || $s === StatusTagihan::Overdue),
                        'warning' => fn($s) => $s instanceof StatusTagihan && $s === StatusTagihan::Pending,
                    ]),
            ])
            ->filters([SelectFilter::make('status')])
            ->defaultSort('tanggal_jatuh_tempo');
    }

    public static function getNavigationIcon(): ?string { return 'heroicon-o-document-text'; }
    public static function getNavigationLabel(): string { return 'Tagihan'; }
    public static function getNavigationGroup(): ?string { return 'Transaksi'; }
    public static function getNavigationSort(): ?int { return 5; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            PembayaranRelationManager::class,
        ];
    }
}
