<?php

namespace App\Filament\Resources\ProspectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class OnboardingsRelationManager extends RelationManager
{
    protected static string $relationship = 'onboarding';

    protected static ?string $title = 'Onboarding';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Onboarding Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('Assigned Supervisor')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select user'),

                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'configuracion_pendiente' => 'Configuración Pendiente',
                                'registro_pendiente' => 'Registro Pendiente',
                                'documentos_pendientes' => 'Documentos Pendientes',
                                'en_revision' => 'En Revisión',
                                'requiere_correcciones' => 'Requiere Correcciones',
                                'completado' => 'Completado',
                            ])
                            ->required(),
                    ])->columns(2),

                Section::make('Fechas')
                    ->schema([
                        DateTimePicker::make('started_at')
                            ->label('Iniciado')
                            ->displayFormat('d/m/Y H:i'),

                        DateTimePicker::make('completed_at')
                            ->label('Completado')
                            ->displayFormat('d/m/Y H:i'),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'configuracion_pendiente',
                        'info' => 'registro_pendiente',
                        'warning' => 'documentos_pendientes',
                        'info' => 'en_revision',
                        'danger' => 'requiere_correcciones',
                        'success' => 'completado',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match($state) {
                            'configuracion_pendiente' => 'Config. Pendiente',
                            'registro_pendiente' => 'Registro Pendiente',
                            'documentos_pendientes' => 'Docs Pendientes',
                            'en_revision' => 'En Revisión',
                            'requiere_correcciones' => 'Requiere Correcciones',
                            'completado' => 'Completado',
                            default => $state
                        };
                    })
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Supervisor')
                    ->sortable(),

                TextColumn::make('started_at')
                    ->label('Iniciado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
