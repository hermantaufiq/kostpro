<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RentalApplicationResource\Pages;
use App\Filament\Admin\Resources\RentalApplicationResource\RelationManagers\TagihanRelationManager;
use App\Models\Penyewaan;
use App\Models\Tagihan;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
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
use App\Enums\StatusPenyewaan;

class RentalApplicationResource extends Resource
{
    protected static ?string $model = Penyewaan::class;

    protected static ?string $recordTitleAttribute = 'kode_penyewaan';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Penyewaan')->schema([
                TextInput::make('kode_penyewaan')->disabled(),
                Select::make('user_id')->relationship('user', 'name')->required(),
                Select::make('kamar_id')->relationship('kamar', 'nama')->required(),
                DatePicker::make('tanggal_masuk')->required(),
                DatePicker::make('tanggal_keluar'),
                TextInput::make('durasi_bulan')->numeric(),
                TextInput::make('harga_bulanan_snapshot')->numeric(),
                TextInput::make('deposit_amount')->numeric(),
            ]),

            Section::make('Status & Catatan')->schema([
                Select::make('status')
                    ->options(fn() => collect(StatusPenyewaan::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()]))
                    ->required(),
                Textarea::make('catatan_penyewa')->rows(3),
                Textarea::make('catatan_admin')->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_penyewaan')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Penyewa'),
                TextColumn::make('kamar.nama')->label('Kamar'),
                TextColumn::make('tanggal_masuk')->date('d/m/Y'),
                BadgeColumn::make('status')
                    ->formatStateUsing(fn($state) => $state instanceof StatusPenyewaan ? $state->label() : $state),
            ])
            ->filters([SelectFilter::make('status')])
            ->actions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn($record) => $record->status === StatusPenyewaan::Pending)
                        ->action(function ($record) {
                            $record->update([
                                'status' => StatusPenyewaan::Approved,
                                'approved_by' => auth()->id(),
                                'tanggal_approval' => now(),
                            ]);
                            
                            // Generate invoices
                            \App\Services\InvoiceService::createMonthlyInvoices($record);
                        })
                        ->successNotificationTitle('Pengajuan disetujui & tagihan dibuat'),

                    Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn($record) => $record->status === StatusPenyewaan::Pending)
                        ->form([
                            \Filament\Forms\Components\Textarea::make('catatan_admin')
                                ->label('Alasan Penolakan')
                                ->required(),
                        ])
                        ->action(fn($record, array $data) => $record->update([
                            'status' => StatusPenyewaan::Rejected,
                            'catatan_admin' => $data['catatan_admin'],
                        ]))
                        ->successNotificationTitle('Pengajuan ditolak'),

                    Action::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-bolt')
                        ->color('info')
                        ->visible(fn($record) => $record->status === StatusPenyewaan::Approved)
                        ->action(fn($record) => $record->update(['status' => StatusPenyewaan::Active]))
                        ->successNotificationTitle('Penyewaan diaktifkan'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getNavigationIcon(): ?string { return 'heroicon-o-document-check'; }
    public static function getNavigationLabel(): string { return 'Pengajuan Sewa'; }
    public static function getNavigationGroup(): ?string { return 'Transaksi'; }
    public static function getNavigationSort(): ?int { return 4; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalApplications::route('/'),
            'create' => Pages\CreateRentalApplication::route('/create'),
            'edit' => Pages\EditRentalApplication::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            TagihanRelationManager::class,
        ];
    }
}
