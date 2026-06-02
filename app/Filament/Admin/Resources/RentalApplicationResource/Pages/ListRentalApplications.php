<?php
namespace App\Filament\Admin\Resources\RentalApplicationResource\Pages;
use App\Filament\Admin\Resources\RentalApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListRentalApplications extends ListRecords
{
    protected static string $resource = RentalApplicationResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
