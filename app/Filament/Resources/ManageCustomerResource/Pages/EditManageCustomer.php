<?php

namespace App\Filament\Resources\ManageCustomerResource\Pages;

use App\Filament\Resources\ManageCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManageCustomer extends EditRecord
{
    protected static string $resource = ManageCustomerResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
