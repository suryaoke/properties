<?php

namespace App\Filament\Resources\ManageIklanResource\Pages;

use App\Filament\Resources\ManageIklanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManageIklans extends ListRecords
{
    protected static string $resource = ManageIklanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
