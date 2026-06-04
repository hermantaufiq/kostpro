<?php

namespace App\Filament\Resources\LayananTambahans\Pages;

use App\Filament\Resources\LayananTambahans\LayananTambahanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLayananTambahans extends ListRecords
{
    protected static string $resource = LayananTambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
