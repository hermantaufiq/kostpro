<?php

namespace App\Filament\Resources;

use App\Models\WaitingList;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class WaitingListResource extends Resource
{
    protected static ?string $model = WaitingList::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-queue-list';
    }

    public static function getNavigationLabel(): string
    {
        return 'Daftar Tunggu';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Properti';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public static function getModelLabel(): string
    {
        return 'Daftar Tunggu';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Tunggu';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Pendaftar')->schema([
                Select::make('kamar_id')
                    ->label('Kamar')
                    ->relationship('kamar', 'nama')
                    ->required(),
                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('no_wa')
                    ->label('No WhatsApp')
                    ->required(),
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']),
                TextInput::make('pekerjaan')
                    ->label('Pekerjaan'),
                TextInput::make('estimasi_masuk')
                    ->label('Estimasi Masuk'),
                Textarea::make('catatan_khusus')
                    ->label('Catatan Khusus')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu'  => 'Menunggu',
                        'dihubungi' => 'Sudah Dihubungi',
                        'batal'     => 'Batal',
                    ])
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kamar.nama')
                    ->label('Kamar Diincar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_wa')
                    ->label('No WhatsApp'),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn ($state) => $state === 'Laki-laki' ? 'info' : 'danger'),
                Tables\Columns\TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('estimasi_masuk')
                    ->label('Estimasi Masuk')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'menunggu'  => 'warning',
                        'dihubungi' => 'success',
                        'batal'     => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'menunggu'  => 'Menunggu',
                        'dihubungi' => 'Sudah Dihubungi',
                        'batal'     => 'Batal',
                        default     => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu'  => 'Menunggu',
                        'dihubungi' => 'Sudah Dihubungi',
                        'batal'     => 'Batal',
                    ]),
            ])
            ->actions([
                Action::make('hubungi_wa')
                    ->label('Hubungi WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn (WaitingList $record) =>
                        'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->no_wa) .
                        '?text=' . rawurlencode(
                            "Halo Kak *{$record->nama}* 👋,\n\nKami dari pengelola kos ingin menginformasikan bahwa kamar *{$record->kamar->nama}* yang Kakak incar sudah *KOSONG* dan tersedia.\n\nApakah Kakak masih berminat? 😊\n\nBalas pesan ini untuk informasi lebih lanjut."
                        )
                    )
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Resources\WaitingListResource\Pages\ListWaitingLists::route('/'),
            'create' => \App\Filament\Resources\WaitingListResource\Pages\CreateWaitingList::route('/create'),
            'edit'   => \App\Filament\Resources\WaitingListResource\Pages\EditWaitingList::route('/{record}/edit'),
        ];
    }
}
