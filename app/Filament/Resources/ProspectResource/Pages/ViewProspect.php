<?php

namespace App\Filament\Resources\ProspectResource\Pages;

use App\Filament\Resources\ProspectResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Notifications\Notification;

class ViewProspect extends ViewRecord
{
    protected static string $resource = ProspectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Link de registro
            Actions\Action::make('registration_link')
                ->label('🔗 Ver Link de Registro')
                ->color('info')
                ->icon('heroicon-m-link')
                ->visible(fn () => $this->record->status === 'enlace_enviado' && $this->record->registrationTokens()->whereNull('used_at')->exists())
                ->action(function () {
                    $token = $this->record->registrationTokens()->whereNull('used_at')->first();
                    if ($token) {
                        $url = url('/completar-registro/' . $token->token);
                        $this->dispatch('copy-to-clipboard', url: $url);
                        Notification::make()
                            ->success()
                            ->title('Link copiado al portapapeles')
                            ->send();
                    }
                }),

            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
