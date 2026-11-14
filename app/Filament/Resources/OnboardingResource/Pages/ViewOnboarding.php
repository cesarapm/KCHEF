<?php

namespace App\Filament\Resources\OnboardingResource\Pages;

use App\Filament\Resources\OnboardingResource;
use Filament\Resources\Pages\ViewRecord;

class ViewOnboarding extends ViewRecord
{
    protected static string $resource = OnboardingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
