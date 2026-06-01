<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Penyewaan;
use Filament\Tables\Columns\TextColumn;

class RecentActivityWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penyewaan::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('kode_penyewaan')
                    ->label('Kode'),
                TextColumn::make('user.name')
                    ->label('Penyewa'),
                TextColumn::make('kamar.nama')
                    ->label('Kamar'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'active' => 'primary',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
