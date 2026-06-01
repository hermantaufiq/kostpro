<?php

namespace App\Filament\Resources\Penyewaans\Pages;

use App\Filament\Resources\Penyewaans\PenyewaanResource;
use Filament\Tables\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPenyewaan extends ViewRecord
{
    protected static string $resource = PenyewaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
