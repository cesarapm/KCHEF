<?php

namespace App\Filament\Resources\ProspectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class ActivityLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'activityLogs';

    protected static ?string $title = 'Historial de Actividades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action')
                    ->label('Action')
                    ->disabled()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->disabled(),

                Forms\Components\TextInput::make('user_id')
                    ->label('User ID')
                    ->disabled(),

                Forms\Components\TextInput::make('ip_address')
                    ->label('IP Address')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(function (string $state): string {
                        return match($state) {
                            'prospect_created' => 'info',
                            'token_generated' => 'success',
                            'status_changed' => 'warning',
                            'client_registered' => 'success',
                            'onboarding_started' => 'info',
                            'onboarding_status_changed' => 'warning',
                            'document_uploaded' => 'info',
                            'document_reviewed' => 'warning',
                            default => 'gray',
                        };
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        return $column->getState();
                    }),

                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha/Hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Las actividades se crean automáticamente, no se pueden crear manualmente
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        Forms\Components\TextInput::make('action')
                            ->label('Action')
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->disabled(),

                        Forms\Components\Textarea::make('old_value')
                            ->label('Valor Anterior')
                            ->disabled(),

                        Forms\Components\Textarea::make('new_value')
                            ->label('New Value')
                            ->disabled(),

                        Forms\Components\TextInput::make('user_id')
                            ->label('User ID')
                            ->disabled(),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),

                        Forms\Components\TextInput::make('user_agent')
                            ->label('User Agent')
                            ->disabled(),

                        Forms\Components\TextInput::make('created_at')
                            ->label('Creado')
                            ->disabled(),
                    ]),
            ])
            ->bulkActions([
                // No se pueden eliminar actividades en lote
            ]);
    }
}
