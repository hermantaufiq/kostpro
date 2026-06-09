<?php

namespace App\Filament\Resources\Penyewaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PenyewaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_penyewaan')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),
                TextColumn::make('kamar.nama')
                    ->label('Kamar')
                    ->searchable(),
                TextColumn::make('tanggal_masuk')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('durasi_bulan')
                    ->label('Durasi (Bulan)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_bulanan_snapshot')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'active' => 'primary',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('deposit_paid')
                    ->label('Deposit')
                    ->boolean(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\StatusPenyewaan::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'pending')
                    ->action(function (\App\Models\Penyewaan $record) {
                        app(\App\Contracts\Services\PenyewaanServiceInterface::class)->approveSewa($record->id, auth()->id());
                    }),
                \Filament\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'pending')
                    ->action(function (\App\Models\Penyewaan $record) {
                        app(\App\Contracts\Services\PenyewaanServiceInterface::class)->rejectSewa($record->id, auth()->id());
                    }),
                \Filament\Actions\Action::make('perpanjang')
                    ->label('Perpanjang')
                    ->icon('heroicon-m-arrow-path')
                    ->color('warning')
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'active')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('tambahan_bulan')
                            ->label('Tambahan Durasi (Bulan)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(12)
                            ->required(),
                    ])
                    ->action(function (\App\Models\Penyewaan $record, array $data) {
                        try {
                            app(\App\Contracts\Services\PenyewaanServiceInterface::class)
                                ->perpanjangKontrak($record->id, (int) $data['tambahan_bulan'], auth()->id());
                            \Filament\Notifications\Notification::make()->title('Kontrak berhasil diperpanjang!')->success()->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
