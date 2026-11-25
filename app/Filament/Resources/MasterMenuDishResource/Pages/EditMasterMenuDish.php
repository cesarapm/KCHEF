<?php

namespace App\Filament\Resources\MasterMenuDishResource\Pages;

use App\Filament\Resources\MasterMenuDishResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterMenuDish extends EditRecord
{
    protected static string $resource = MasterMenuDishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

