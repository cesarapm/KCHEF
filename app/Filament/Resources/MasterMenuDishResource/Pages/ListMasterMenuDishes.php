<?php

namespace App\Filament\Resources\MasterMenuDishResource\Pages;

use App\Filament\Resources\MasterMenuDishResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterMenuDishes extends ListRecords
{
    protected static string $resource = MasterMenuDishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

