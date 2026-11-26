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
            // Change status button
            Actions\Action::make('change_status')
                ->label('Change Status')
                ->color('warning')
                ->icon('heroicon-m-arrow-path')
                ->form([
                    \Filament\Forms\Components\Select::make('new_status')
                        ->label('New Status')
                        ->options([
                            'pendiente' => 'Pending',
                            'aprobado' => 'Approved',
                            'enlace_enviado' => 'Link Sent',
                            'cliente_registrado' => 'Client Registered',
                            'en_onboarding' => 'In Onboarding',
                            'completado' => 'Completed',
                            'rechazado' => 'Rejected',
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
                        ->title("Status updated to: $newStatus")
                        ->send();
                    $this->redirect(ProspectResource::getUrl('index'));
                })
                ->requiresConfirmation(),

            // Accept/approve button
            Actions\Action::make('approve')
                ->label('✅ Approve')
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
                        ->title('Prospect approved successfully')
                        ->send();
                    $this->redirect(ProspectResource::getUrl('index'));
                })
                ->requiresConfirmation(),

            // Generate invitation button
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
                        ->title('Token generated and email sent')
                        ->send();
                    $this->redirect(ProspectResource::getUrl('edit', [$this->record->id]));
                })
                ->requiresConfirmation(),

            // View/copy registration link button
            Actions\Action::make('show_registration_link')
                ->label('📋 Registration Link')
                ->color('gray')
                ->icon('heroicon-m-document-text')
                ->visible(fn () => $this->record->status === 'enlace_enviado' && $this->record->registrationTokens()->whereNull('used_at')->exists())
                ->modalHeading('📋 Share Registration Link')
                ->modalSubheading('Select and copy the content you want to share')
                ->modalSubmitActionLabel('Close')
                ->modalCancelActionLabel('')
                ->form([
                    \Filament\Forms\Components\TextInput::make('registration_url')
                        ->label('🔗 Registration Link')
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
                        ->label('📝 Suggested Message')
                        ->default(function () {
                            $token = $this->record->registrationTokens()->whereNull('used_at')->first();
                            $url = $token ? url('/completar-registro/' . $token->token) : '';
                            return "Hello " . $this->record->full_name . ",\n\nYour invitation to complete registration in CHEF is ready.\n\nClick the following link to continue:\n" . $url . "\n\nThis link is valid for 7 days.\n\nRegards,\nCHEF Team";
                        })
                        ->disabled()
                        ->rows(6)
                        ->helperText('Copy and send via email, WhatsApp, or SMS'),
                ])
                ->action(function () {
                    // Display-only modal
                }),

            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Log status changes
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
