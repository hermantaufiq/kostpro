<?php

namespace App\Filament\Admin\Resources\InvoiceResource\RelationManagers;

use App\Enums\StatusPembayaran;
use App\Enums\MetodePembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_diterima')
                    ->label('Jumlah (Rp)')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('metode')
                    ->label('Metode Pembayaran')
                    ->options(MetodePembayaran::class)
                    ->required(),
                Forms\Components\TextInput::make('xendit_invoice_id')
                    ->label('Xendit Invoice ID')
                    ->maxLength(255),
                Forms\Components\TextInput::make('channel_code')
                    ->label('Channel Code')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(StatusPembayaran::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kode_pembayaran')
            ->columns([
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_diterima')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode')
                    ->formatStateUsing(fn ($state) => $state instanceof MetodePembayaran ? $state->label() : $state)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => fn($state) => $state === StatusPembayaran::Success,
                        'warning' => fn($state) => $state === StatusPembayaran::Pending,
                        'danger' => fn($state) => $state === StatusPembayaran::Failed,
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusPembayaran::class),
                Tables\Filters\SelectFilter::make('metode')
                    ->options(MetodePembayaran::class),
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
