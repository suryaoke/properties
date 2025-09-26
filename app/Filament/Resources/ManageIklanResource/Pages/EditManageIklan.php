<?php

namespace App\Filament\Resources\ManageIklanResource\Pages;

use App\Filament\Resources\ManageIklanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManageIklan extends EditRecord
{
    protected static string $resource = ManageIklanResource::class;
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
