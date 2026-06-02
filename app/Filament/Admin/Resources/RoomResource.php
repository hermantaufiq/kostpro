<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoomResource\Pages;
use App\Filament\Admin\Resources\RoomResource\RelationManagers\FotoKamarRelationManager;
use App\Models\Kamar;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Enums\StatusKamar;
use App\Enums\TipeKamar;

class RoomResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('kode_kamar')
                            ->label('Kode Kamar')
                            ->required()
                            ->maxLength(50)
                            ->unique(Kamar::class, 'kode_kamar', ignoreRecord: true),
                        TextInput::make('nama')
                            ->label('Nama Kamar')
                            ->required()
                            ->maxLength(255),
                        Select::make('tipe')
                            ->label('Tipe Kamar')
                            ->options(fn() => collect(TipeKamar::cases())->mapWithKeys(
                                fn($case) => [$case->value => $case->label()]
                            ))
                            ->required(),
                        Select::make('lantai')
                            ->label('Lantai')
                            ->options(collect(range(1, 10))->mapWithKeys(fn($n) => [$n => "Lantai $n"]))
                            ->required(),
                    ])->columns(2),

                Section::make('Spesifikasi')
                    ->schema([
                        TextInput::make('luas')
                            ->label('Luas (m²)')
                            ->numeric()
                            ->required(),
                        TextInput::make('harga_bulanan')
                            ->label('Harga Bulanan (Rp)')
                            ->numeric()
                            ->required(),
                        TextInput::make('harga_deposit')
                            ->label('Harga Deposit (Rp)')
                            ->numeric()
                            ->required(),
                    ])->columns(3),

                Section::make('Foto')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Upload Foto Kamar')
                            ->multiple()
                            ->maxFiles(10)
                            ->image()
                            ->directory('rooms')
                            ->reorderable(),
                    ]),

                Section::make('Deskripsi & Fasilitas')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(4)->columnSpanFull(),
                        \Filament\Forms\Components\CheckboxList::make('fasilitasMaster')
                            ->relationship('fasilitasMaster', 'nama')
                            ->label('Fasilitas (Pilih dari Master Data)')
                            ->columns(3)
                            ->columnSpanFull(),
                        Checkbox::make('is_featured')
                            ->label('Tampilkan di Halaman Utama'),
                    ]),

                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(fn() => collect(StatusKamar::cases())->mapWithKeys(
                                fn($case) => [$case->value => $case->label()]
                            ))
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_kamar')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->formatStateUsing(fn($state) => $state instanceof TipeKamar ? $state->label() : $state)
                    ->badge()
                    ->color(fn($state) => $state instanceof TipeKamar ? $state->color() : 'gray'),
                TextColumn::make('lantai')
                    ->label('Lantai')
                    ->sortable(),
                TextColumn::make('luas')
                    ->label('Luas (m²)')
                    ->sortable(),
                TextColumn::make('harga_bulanan')
                    ->label('Harga Bulanan')
                    ->money('IDR', 0)
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state instanceof StatusKamar ? $state->label() : $state)
                    ->colors([
                        'success' => fn($state) => $state instanceof StatusKamar && $state === StatusKamar::Tersedia,
                        'danger' => fn($state) => $state instanceof StatusKamar && $state === StatusKamar::Terisi,
                        'warning' => fn($state) => $state instanceof StatusKamar && $state === StatusKamar::Maintenance,
                        'info' => fn($state) => $state instanceof StatusKamar && $state === StatusKamar::Reserved,
                    ]),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->options(fn() => collect(TipeKamar::cases())->mapWithKeys(
                        fn($case) => [$case->value => $case->label()]
                    )),
                SelectFilter::make('status')
                    ->options(fn() => collect(StatusKamar::cases())->mapWithKeys(
                        fn($case) => [$case->value => $case->label()]
                    )),
                SelectFilter::make('lantai')
                    ->options(collect(range(1, 10))->mapWithKeys(fn($n) => [$n => "Lantai $n"])),
            ])
            ->defaultSort('nama');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home-modern';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kamar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            FotoKamarRelationManager::class,
        ];
    }
}

