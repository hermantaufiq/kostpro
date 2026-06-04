<?php

namespace App\Filament\Resources\LayananTambahans\Pages;

use App\Filament\Resources\LayananTambahans\LayananTambahanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLayananTambahan extends EditRecord
{
    protected static string $resource = LayananTambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
