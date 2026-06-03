<?php

namespace App\Filament\Resources\Keluhans\Pages;

use App\Filament\Resources\Keluhans\KeluhanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKeluhan extends ViewRecord
{
    protected static string $resource = KeluhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
