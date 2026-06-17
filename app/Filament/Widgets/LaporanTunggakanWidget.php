<?php

namespace App\Filament\Widgets;

use App\Models\Tagihan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LaporanTunggakanWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Tagihan Belum Dibayar (Tunggakan)')
            ->query(
                Tagihan::with(['user', 'penyewaan.kamar'])
                    ->whereIn('status', ['unpaid', 'overdue'])
                    ->orderBy('tanggal_jatuh_tempo')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penghuni')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penyewaan.kamar.nama')
                    ->label('Kamar'),
                Tables\Columns\TextColumn::make('total_tagihan')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->badge()
                    ->color('danger'),
            ])
            ->paginated([5]);
    }
}
