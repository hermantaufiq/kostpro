<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Enums\StatusPembayaran;
use App\Enums\MetodePembayaran;

class PaymentResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $recordTitleAttribute = 'kode_pembayaran';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Pembayaran')->schema([
                TextInput::make('kode_pembayaran')->disabled(),
                Select::make('tagihan_id')->relationship('tagihan', 'kode_tagihan')->required(),
                TextInput::make('jumlah')->numeric()->required(),
                Select::make('metode')
                    ->options(fn() => collect(MetodePembayaran::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()]))
                    ->required(),
                DateTimePicker::make('paid_at')->label('Tanggal Pembayaran'),
            ]),

            Section::make('Status & Xendit')->schema([
                Select::make('status')
                    ->options(fn() => collect(StatusPembayaran::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()]))
                    ->required(),
                TextInput::make('xendit_invoice_id')->label('Xendit Invoice ID'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pembayaran')->searchable()->sortable(),
                TextColumn::make('tagihan.kode_tagihan')->label('Tagihan'),
                TextColumn::make('tagihan.penyewaan.user.name')->label('Penyewa'), // Added penyewa
                TextColumn::make('jumlah')->money('IDR', 0),
                TextColumn::make('metode')
                    ->formatStateUsing(fn($state) => $state instanceof MetodePembayaran ? $state->label() : $state),
                TextColumn::make('paid_at')->label('Tanggal Pembayaran')->datetime('d/m/Y H:i'),
                BadgeColumn::make('status')
                    ->formatStateUsing(fn($state) => $state instanceof StatusPembayaran ? $state->label() : $state)
                    ->colors([
                        'success' => fn($s) => $s instanceof StatusPembayaran && $s === StatusPembayaran::Success,
                        'warning' => fn($s) => $s instanceof StatusPembayaran && $s === StatusPembayaran::Pending,
                        'danger' => fn($s) => $s instanceof StatusPembayaran && $s === StatusPembayaran::Failed,
                    ]),
            ])
            ->filters([SelectFilter::make('status')])
            ->actions([
                ActionGroup::make([
                    Action::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn($record) => $record->status === StatusPembayaran::Pending)
                        ->action(function ($record) {
                            $record->update(['status' => StatusPembayaran::Success, 'paid_at' => now()]);
                            $record->tagihan->update(['status' => \App\Enums\StatusTagihan::Paid, 'tanggal_bayar' => now()]);
                        })
                        ->successNotificationTitle('Pembayaran dikonfirmasi'),

                    Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn($record) => $record->status === StatusPembayaran::Pending)
                        ->action(fn($record) => $record->update(['status' => StatusPembayaran::Failed]))
                        ->successNotificationTitle('Pembayaran ditolak'),
                ]),
            ])
            ->defaultSort('tanggal_pembayaran', 'desc');
    }

    public static function getNavigationIcon(): ?string { return 'heroicon-o-credit-card'; }
    public static function getNavigationLabel(): string { return 'Pembayaran'; }
    public static function getNavigationGroup(): ?string { return 'Transaksi'; }
    public static function getNavigationSort(): ?int { return 6; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
