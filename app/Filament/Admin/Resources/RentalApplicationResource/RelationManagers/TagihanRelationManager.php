<?php

namespace App\Filament\Admin\Resources\RentalApplicationResource\RelationManagers;

use App\Enums\StatusTagihan;
use App\Enums\StatusPembayaran;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagihanRelationManager extends RelationManager
{
    protected static string $relationship = 'tagihan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_jatuh_tempo')
                    ->label('Tanggal Jatuh Tempo')
                    ->required(),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal (Rp)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('denda')
                    ->label('Denda (Rp)')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(StatusTagihan::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('denda')
                    ->label('Denda')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => StatusTagihan::Lunas->value,
                        'warning' => StatusTagihan::JatuhTempo->value,
                        'danger' => StatusTagihan::Overdue->value,
                        'primary' => StatusTagihan::Pending->value,
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusTagihan::class),
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
