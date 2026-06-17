<?php

namespace App\Filament\Resources\Penyewaans\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

                \Filament\Tables\Filters\SelectFilter::make('periode_terbaru')
                    ->label('Data Terbaru')
                    ->placeholder('Semua Periode')
                    ->options([
                        'today'    => 'Hari Ini',
                        'week'     => 'Minggu Ini',
                        'month'    => 'Bulan Ini',
                        '3months'  => '3 Bulan Terakhir',
                        '6months'  => '6 Bulan Terakhir',
                        'year'     => 'Tahun Ini',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        return match ($value) {
                            'today'   => $query->whereDate('created_at', Carbon::today()),
                            'week'    => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                            'month'   => $query->whereMonth('created_at', Carbon::now()->month)
                                              ->whereYear('created_at', Carbon::now()->year),
                            '3months' => $query->where('created_at', '>=', Carbon::now()->subMonths(3)),
                            '6months' => $query->where('created_at', '>=', Carbon::now()->subMonths(6)),
                            'year'    => $query->whereYear('created_at', Carbon::now()->year),
                            default   => $query,
                        };
                    }),

                Filter::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d M Y'),
                        \Filament\Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d M Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari'], fn (Builder $q, $date) => $q->whereDate('tanggal_masuk', '>=', $date))
                            ->when($data['sampai'], fn (Builder $q, $date) => $q->whereDate('tanggal_masuk', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) {
                            $indicators['dari'] = 'Tanggal Masuk dari ' . Carbon::parse($data['dari'])->translatedFormat('d M Y');
                        }
                        if ($data['sampai'] ?? null) {
                            $indicators['sampai'] = 'Tanggal Masuk sampai ' . Carbon::parse($data['sampai'])->translatedFormat('d M Y');
                        }
                        return $indicators;
                    }),

                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
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
                \Filament\Actions\Action::make('terbitkan_tagihan')
                    ->label('Terbitkan Tagihan')
                    ->icon('heroicon-m-document-plus')
                    ->color('warning')
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'active')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('tambahan_bulan')
                            ->label('Untuk Durasi Berapa Bulan?')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(12)
                            ->required(),
                    ])
                    ->action(function (\App\Models\Penyewaan $record, array $data) {
                        try {
                            app(\App\Contracts\Services\PenyewaanServiceInterface::class)
                                ->terbitkanTagihanBerikutnya($record->id, (int) $data['tambahan_bulan']);
                            \Filament\Notifications\Notification::make()->title('Tagihan perpanjangan berhasil diterbitkan!')->success()->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),
                \Filament\Actions\Action::make('berhenti_sewa')
                    ->label('Berhenti Sewa')
                    ->icon('heroicon-m-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Berhenti Sewa')
                    ->modalDescription('Apakah Anda yakin ingin mematikan perpanjangan otomatis untuk penyewaan ini? Tagihan bulan depan tidak akan dibuat.')
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'active' && $record->is_auto_renewal)
                    ->action(function (\App\Models\Penyewaan $record) {
                        $record->update(['is_auto_renewal' => false]);
                        \Filament\Notifications\Notification::make()->title('Perpanjangan otomatis dinonaktifkan')->success()->send();
                    }),
                \Filament\Actions\Action::make('aktifkan_auto_renewal')
                    ->label('Auto-Renewal On')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'active' && !$record->is_auto_renewal)
                    ->action(function (\App\Models\Penyewaan $record) {
                        $record->update(['is_auto_renewal' => true]);
                        \Filament\Notifications\Notification::make()->title('Perpanjangan otomatis diaktifkan kembali')->success()->send();
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
