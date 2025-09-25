<?php

namespace App\Filament\Resources\WhyResource\Pages;

use App\Filament\Resources\WhyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhy extends EditRecord
{
    protected static string $resource = WhyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
