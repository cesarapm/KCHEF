<?php

namespace App\Filament\Resources\ProspectResource\Pages;

use App\Filament\Resources\ProspectResource;
use App\Models\Prospect;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditProspect extends EditRecord
{
    protected static string $resource = ProspectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botón Cambiar Estado
            Actions\Action::make('change_status')
                ->label('Change Status')
                ->color('warning')
                ->icon('heroicon-m-arrow-path')
                ->form([
                    \Filament\Forms\Components\Select::make('new_status')
                        ->label('New Status')
                        ->options([
                            'pendiente' => 'Pendiente',
                            'aprobado' => 'Aprobado',
                            'enlace_enviado' => 'Enlace Enviado',
                            'cliente_registrado' => 'Cliente Registrado',
                            'en_onboarding' => 'En Onboarding',
                            'completado' => 'Completado',
                            'rechazado' => 'Rechazado',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $oldStatus = $this->record->status;
                    $newStatus = $data['new_status'];

                    $this->record->update(['status' => $newStatus]);
                    $this->record->activityLogs()->create([
                        'action' => 'status_changed',
                        'description' => 'Status manually changed by admin',
                        'old_value' => ['status' => $oldStatus],
                        'new_value' => ['status' => $newStatus],
                    ]);

                    Notification::make()
                        ->success()
                        ->title("Estado actualizado a: $newStatus")
                        ->send();
                    $this->redirect(ProspectResource::getUrl('index'));
                })
                ->requiresConfirmation(),

            // Botón Aceptar/Aprobar
            Actions\Action::make('approve')
                ->label('✅ Aceptar')
                ->color('success')
                ->icon('heroicon-m-check-circle')
                ->visible(fn () => $this->record->status === 'pendiente')
                ->action(function () {
                    $this->record->update(['status' => 'aprobado']);
                    $this->record->activityLogs()->create([
                        'action' => 'status_changed',
                        'description' => 'Prospect approved by admin',
                        'old_value' => ['status' => 'pendiente'],
                        'new_value' => ['status' => 'aprobado'],
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Prospect aprobado correctamente')
                        ->send();
                    $this->redirect(ProspectResource::getUrl('index'));
                })
                ->requiresConfirmation(),

            // Botón Generar Invitación
            Actions\Action::make('generate_token')
                ->label('Generate Invitation')
                ->color('info')
                ->icon('heroicon-m-link')
                ->visible(fn () => $this->record->status === 'aprobado')
                ->action(function () {
                    $token = \Illuminate\Support\Str::random(64);
                    $this->record->registrationTokens()->create([
                        'token' => $token,
                        'expires_at' => now()->addDays(7),
                    ]);
                    $this->record->update(['status' => 'enlace_enviado']);
                    $this->record->activityLogs()->create([
                        'action' => 'token_generated',
                        'description' => 'Invitation token generated',
                        'new_value' => ['token' => substr($token, 0, 20) . '...'],
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Token generado y email enviado')
                        ->send();
                    $this->redirect(ProspectResource::getUrl('edit', [$this->record->id]));
                })
                ->requiresConfirmation(),

            // Botón Ver/Copiar Link de Registro
            Actions\Action::make('show_registration_link')
                ->label('📋 Link de Registro')
                ->color('gray')
                ->icon('heroicon-m-document-text')
                ->visible(fn () => $this->record->status === 'enlace_enviado' && $this->record->registrationTokens()->whereNull('used_at')->exists())
                ->modalHeading('📋 Compartir Link de Registro')
                ->modalSubheading('Selecciona y copia el contenido que deseas compartir')
                ->modalSubmitActionLabel('Cerrar')
                ->modalCancelActionLabel('')
                ->form([
                    \Filament\Forms\Components\TextInput::make('registration_url')
                        ->label('🔗 Link de Registro')
                        ->default(function () {
                            $token = $this->record->registrationTokens()->whereNull('used_at')->first();
                            return $token ? url('/completar-registro/' . $token->token) : '';
                        })
                        ->disabled()
                        ->helperText('Valid for 7 days | Select with Ctrl+A and copy with Ctrl+C'),

                    \Filament\Forms\Components\TextInput::make('prospect_email')
                        ->label('Prospect Email')
                        ->default(fn () => $this->record->email)
                        ->disabled()
                        ->helperText('Address to send the link'),

                    \Filament\Forms\Components\Textarea::make('email_text')
                        ->label('📝 Mensaje Sugerido')
                        ->default(function () {
                            $token = $this->record->registrationTokens()->whereNull('used_at')->first();
                            $url = $token ? url('/completar-registro/' . $token->token) : '';
                            return "Hola " . $this->record->full_name . ",\n\nTu invitación para completar el registro en CHEF está lista.\n\nHaz clic en el siguiente link para continuar:\n" . $url . "\n\nEste link es válido por 7 días.\n\nSaludos,\nEquipo CHEF";
                        })
                        ->disabled()
                        ->rows(6)
                        ->helperText('Copiar y enviar por email, WhatsApp o SMS'),
                ])
                ->action(function () {
                    // Modal solo visualiza
                }),

            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Log cambios de estado
        if ($this->record->status !== $data['status']) {
            $this->record->activityLogs()->create([
                'action' => 'status_changed',
                'description' => 'Status changed from form',
                'old_value' => ['status' => $this->record->status],
                'new_value' => ['status' => $data['status']],
            ]);
        }
        return $data;
    }
}
