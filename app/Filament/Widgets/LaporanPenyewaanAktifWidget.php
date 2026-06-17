<?php

namespace App\Filament\Widgets;

use App\Models\Penyewaan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LaporanPenyewaanAktifWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Penyewaan Aktif Terbaru')
            ->query(
                Penyewaan::with(['user', 'kamar'])
                    ->where('status', 'active')
                    ->orderByDesc('tanggal_masuk')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penghuni')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kamar.nama')
                    ->label('Kamar'),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tgl Masuk')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('durasi_bulan')
                    ->label('Durasi')
                    ->formatStateUsing(fn ($state) => $state . ' Bulan')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated([5]);
    }
}
