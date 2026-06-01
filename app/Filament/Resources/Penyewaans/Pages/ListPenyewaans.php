<?php

namespace App\Filament\Resources\Penyewaans\Pages;

use App\Filament\Resources\Penyewaans\PenyewaanResource;
use Filament\Tables\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenyewaans extends ListRecords
{
    protected static string $resource = PenyewaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
