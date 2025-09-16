<?php

namespace App\Filament\Resources\ManageCustomerResource\Pages;

use App\Filament\Resources\ManageCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManageCustomers extends ListRecords
{
    protected static string $resource = ManageCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
           Actions\CreateAction::make()
               ->label('Create'),
        ];
    }
}
