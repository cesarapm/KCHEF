<?php

namespace App\Filament\Resources\OrderServiceResource\Pages;

use App\Filament\Pages\ServicesCalendar;
use App\Filament\Resources\OrderServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderServices extends ListRecords
{
    protected static string $resource = OrderServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('calendar')
                ->label('Monthly Calendar')
                ->icon('heroicon-o-calendar')
                ->url(ServicesCalendar::getUrl()),
            Actions\CreateAction::make(),
        ];
    }
}

