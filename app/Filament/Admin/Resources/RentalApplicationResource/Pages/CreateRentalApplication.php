<?php
namespace App\Filament\Admin\Resources\RentalApplicationResource\Pages;
use App\Filament\Admin\Resources\RentalApplicationResource;
use Filament\Resources\Pages\CreateRecord;
class CreateRentalApplication extends CreateRecord
{
    protected static string $resource = RentalApplicationResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
