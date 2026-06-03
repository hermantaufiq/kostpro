<?php

namespace App\Filament\Resources\Keluhans\Pages;

use App\Filament\Resources\Keluhans\KeluhanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKeluhans extends ListRecords
{
    protected static string $resource = KeluhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
