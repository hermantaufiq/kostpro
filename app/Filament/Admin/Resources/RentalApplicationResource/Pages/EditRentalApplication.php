<?php
namespace App\Filament\Admin\Resources\RentalApplicationResource\Pages;
use App\Filament\Admin\Resources\RentalApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditRentalApplication extends EditRecord
{
    protected static string $resource = RentalApplicationResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
