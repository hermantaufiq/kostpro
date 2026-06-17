<?php

namespace App\Filament\Resources\Vouchers\Tables;

use App\Enums\StatusVoucher;
use App\Models\Voucher;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('kode_voucher')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->fontFamily('mono'),
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nominal_diskon')
                    ->label('Nominal Diskon')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (StatusVoucher $state): string => $state->color()),
                TextColumn::make('sumber')
                    ->label('Sumber')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pembayaran_tepat_waktu' => 'Bayar Tepat Waktu',
                        'streak_tepat_waktu'     => 'Streak 3× Tepat Waktu',
                        'manual_admin'           => 'Manual Admin',
                        default                  => ucwords(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color('gray'),
                TextColumn::make('berlaku_sampai')
                    ->label('Berlaku Sampai')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->color(fn (Voucher $record): string => $record->isUsable() ? 'success' : 'danger'),
                TextColumn::make('digunakan_pada')
                    ->label('Digunakan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusVoucher::class),
                SelectFilter::make('sumber')
                    ->options([
                        'pembayaran_tepat_waktu' => 'Bayar Tepat Waktu',
                        'streak_tepat_waktu'     => 'Streak 3× Tepat Waktu',
                        'manual_admin'           => 'Manual Admin',
                    ]),
                SelectFilter::make('user_id')
                    ->label('Penyewa')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('kadaluarsa')
                    ->label('Tandai Kadaluarsa')
                    ->icon('heroicon-m-clock')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Voucher $record) => $record->status === StatusVoucher::Aktif)
                    ->action(fn (Voucher $record) => $record->update(['status' => StatusVoucher::Kadaluarsa])),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
