<?php

namespace App\Filament\Resources\ManageCustomerResource\Pages;

use App\Filament\Resources\ManageCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateManageCustomer extends CreateRecord
{
    protected static string $resource = ManageCustomerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
