<?php

namespace App\Filament\Admin\Resources\TenantResource\RelationManagers;

use App\Enums\StatusPenyewaan;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PenyewaanRelationManager extends RelationManager
{
    protected static string $relationship = 'penyewaan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kamar_id')
                    ->label('Kamar')
                    ->options(Kamar::pluck('nomor_kamar', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->required(),
                Forms\Components\TextInput::make('harga_sewa')
                    ->label('Harga Sewa (Rp)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('durasi_sewa')
                    ->label('Durasi (bulan)')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(StatusPenyewaan::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('kamar.nomor_kamar')
                    ->label('Kamar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Masuk')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_keluar')
                    ->label('Keluar')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_sewa')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => StatusPenyewaan::Active->value,
                        'warning' => StatusPenyewaan::Pending->value,
                        'info' => StatusPenyewaan::Completed->value,
                        'danger' => StatusPenyewaan::Rejected->value,
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusPenyewaan::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
