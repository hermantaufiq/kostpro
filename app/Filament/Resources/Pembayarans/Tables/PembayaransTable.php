<?php

namespace App\Filament\Resources\Pembayarans\Tables;

use App\Enums\MetodePembayaran;
use App\Enums\StatusPembayaran;
use App\Models\Pembayaran;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pembayaran')
                    ->label('Kode Bayar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('tagihan.kode_tagihan')
                    ->label('No. Invoice')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),
                TextColumn::make('tagihan.penyewaan.user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof MetodePembayaran ? $state->label() : ($state ?? '-'))
                    ->color(fn ($state): string => match ($state instanceof MetodePembayaran ? $state->value : $state) {
                        'virtual_account' => 'info',
                        'qris'            => 'success',
                        'ewallet'         => 'primary',
                        'credit_card'     => 'warning',
                        'retail'          => 'gray',
                        default           => 'gray',
                    }),
                TextColumn::make('jumlah')
                    ->label('Jumlah Bayar')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match (is_object($state) ? $state->value : $state) {
                        'pending'  => 'warning',
                        'success'  => 'success',
                        'failed'   => 'danger',
                        'expired'  => 'gray',
                        'refunded' => 'primary',
                        default    => 'gray',
                    }),
                TextColumn::make('paid_at')
                    ->label('Tgl Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusPembayaran::class),

                SelectFilter::make('periode_terbaru')
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

                Filter::make('paid_at')
                    ->label('Tanggal Bayar')
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
                            ->when($data['dari'], fn (Builder $q, $date) => $q->whereDate('paid_at', '>=', $date))
                            ->when($data['sampai'], fn (Builder $q, $date) => $q->whereDate('paid_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) {
                            $indicators['dari'] = 'Tgl Bayar dari ' . Carbon::parse($data['dari'])->translatedFormat('d M Y');
                        }
                        if ($data['sampai'] ?? null) {
                            $indicators['sampai'] = 'Tgl Bayar sampai ' . Carbon::parse($data['sampai'])->translatedFormat('d M Y');
                        }
                        return $indicators;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('approve_manual')
                    ->label('Verifikasi')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Pembayaran $record): bool =>
                        $record->status instanceof \BackedEnum
                            ? $record->status->value === 'pending'
                            : $record->status === 'pending'
                    )
                    ->action(function (Pembayaran $record): void {
                        $record->update([
                            'status'  => StatusPembayaran::Success,
                            'paid_at' => now(),
                        ]);
                        if ($record->tagihan) {
                            $record->tagihan->update([
                                'status'       => 'paid',
                                'tanggal_bayar' => now(),
                            ]);
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(\App\Filament\Exports\PembayaranExporter::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\PembayaranExporter::class),
                ]),
            ]);
    }
}
