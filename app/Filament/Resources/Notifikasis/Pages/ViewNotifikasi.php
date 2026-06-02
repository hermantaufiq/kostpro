<?php

namespace App\Filament\Resources\Notifikasis\Pages;

use App\Filament\Resources\Notifikasis\NotifikasiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNotifikasi extends ViewRecord
{
    protected static string $resource = NotifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
