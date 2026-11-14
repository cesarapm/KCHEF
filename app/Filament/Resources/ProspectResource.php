<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProspectResource\Pages;
use App\Filament\Resources\ProspectResource\RelationManagers;
use App\Models\Prospect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProspectResource extends Resource
{
    protected static ?string $model = Prospect::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Prospects';

    protected static ?string $modelLabel = 'Prospect';

    protected static ?string $pluralModelLabel = 'Prospects';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->description('Basic prospect data')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(false),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique('prospects', 'email', ignoreRecord: true)
                            ->maxLength(255)
                            ->disabled(false),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->required()
                            ->maxLength(20)
                            ->disabled(false),

                        TextInput::make('city')
                            ->label('City')
                            ->maxLength(255)
                            ->disabled(false),
                    ])->columns(2),

                Section::make('Status and Management')
                    ->description('Prospect status in the conversion flow')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pendiente' => 'Pending',
                                'aprobado' => 'Approved',
                                'enlace_enviado' => 'Link Sent',
                                'cliente_registrado' => 'Client Registered',
                                'en_onboarding' => 'In Onboarding',
                                'completado' => 'Completed',
                                'rechazado' => 'Rejected',
                            ])
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->disabled(false),

                        Select::make('user_id')
                            ->label('Assigned to')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select user')
                            ->disabled(false),

                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->disabled(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobado',
                        'info' => 'enlace_enviado',
                        'warning' => 'cliente_registrado',
                        'info' => 'en_onboarding',
                        'success' => 'completado',
                        'danger' => 'rechazado',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match($state) {
                            'pendiente' => 'Pending',
                            'aprobado' => 'Approved',
                            'enlace_enviado' => 'Link Sent',
                            'cliente_registrado' => 'Client Registered',
                            'en_onboarding' => 'In Onboarding',
                            'completado' => 'Completed',
                            'rechazado' => 'Rejected',
                            default => $state
                        };
                    })
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Assigned to')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pendiente' => 'Pending',
                        'aprobado' => 'Approved',
                        'enlace_enviado' => 'Link Sent',
                        'cliente_registrado' => 'Client Registered',
                        'en_onboarding' => 'In Onboarding',
                        'completado' => 'Completed',
                        'rechazado' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // Aprobar prospect
                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Prospect $record) => $record->status === 'pendiente')
                    ->action(function (Prospect $record) {
                        $record->update(['status' => 'aprobado']);
                        $record->activityLogs()->create([
                            'action' => 'status_changed',
                            'description' => 'Prospecto aprobado por admin',
                            'old_value' => ['status' => 'pendiente'],
                            'new_value' => ['status' => 'aprobado'],
                        ]);
                    })
                    ->requiresConfirmation(),

                // Generar token
                Action::make('generate_token')
                    ->label('Generate Invitation')
                    ->icon('heroicon-m-link')
                    ->color('info')
                    ->visible(fn (Prospect $record) => $record->status === 'aprobado')
                    ->form([
                        Forms\Components\Placeholder::make('info')
                            ->content('Se generará un token válido por 7 días para que el usuario complete su registro.'),
                    ])
                    ->action(function (Prospect $record) {
                        $token = \Illuminate\Support\Str::random(64);
                        $record->registrationTokens()->create([
                            'token' => $token,
                            'expires_at' => now()->addDays(7),
                        ]);
                        $record->update(['status' => 'enlace_enviado']);
                        $record->activityLogs()->create([
                            'action' => 'token_generated',
                            'description' => 'Invitation token generated',
                            'new_value' => ['token' => substr($token, 0, 20) . '...'],
                        ]);
                    })
                    ->successNotificationTitle('Token generado exitosamente')
                    ->requiresConfirmation(),

                // Rechazar prospect
                Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (Prospect $record) => in_array($record->status, ['pendiente', 'aprobado']))
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Motivo del Rechazo')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function (Prospect $record, array $data) {
                        $record->update([
                            'status' => 'rechazado',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        $record->activityLogs()->create([
                            'action' => 'rejected',
                            'description' => 'Prospecto rechazado: ' . $data['rejection_reason'],
                            'old_value' => ['status' => $record->status],
                            'new_value' => ['status' => 'rechazado'],
                        ]);
                    })
                    ->successNotificationTitle('Prospecto rechazado')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OnboardingsRelationManager::class,
            RelationManagers\ActivityLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProspects::route('/'),
            'create' => Pages\CreateProspect::route('/create'),
            'view' => Pages\ViewProspect::route('/{record}'),
            'edit' => Pages\EditProspect::route('/{record}/edit'),
        ];
    }
}
