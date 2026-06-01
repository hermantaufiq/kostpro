<?php

namespace App\Filament\Resources\Notifikasis\Pages;

use App\Filament\Resources\Notifikasis\NotifikasiResource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditNotifikasi extends EditRecord
{
    protected static string $resource = NotifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
