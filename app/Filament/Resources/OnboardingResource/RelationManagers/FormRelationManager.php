<?php

namespace App\Filament\Resources\OnboardingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FormRelationManager extends RelationManager
{
    protected static string $relationship = 'form';

    protected static ?string $recordTitleAttribute = 'id';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('🍽️ Kitchen Profile')
                    ->description('Tell us about your food preferences to personalize your experience')
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                // INFORMACIÓN BÁSICA
                                Infolists\Components\Section::make('Basic Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('primary_clients')
                                            ->label('Main Clients')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),

                                        Infolists\Components\TextEntry::make('household_address')
                                            ->label('Home Address')
                                            ->columnSpan(1),

                                        Infolists\Components\TextEntry::make('start_date')
                                            ->label('Start Date')
                                            ->columnSpan(1),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->collapsed(false),

                                // COMPOSICIÓN DEL HOGAR
                                Infolists\Components\Section::make('👨👧👦 Household Composition')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('number_of_adults')
                                            ->label('👨 Adults')
                                            ->formatStateUsing(fn($state) => $state ?? 'N/A'),

                                        Infolists\Components\TextEntry::make('number_of_kids')
                                            ->label('👧 Children')
                                            ->formatStateUsing(fn($state) => $state ?? 'N/A'),

                                        Infolists\Components\TextEntry::make('kids_ages')
                                            ->label('🎂 Ages')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(3)
                                    ->collapsible()
                                    ->collapsed(false),

                                // OBJETIVOS DEL HOGAR
                                Infolists\Components\Section::make('🎯 Household Goals')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('household_goals')
                                            ->label('Objectives')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),

                                        Infolists\Components\TextEntry::make('household_goals_other')
                                            ->label('📝 Custom')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // PREFERENCIAS DIETÉTICAS
                                Infolists\Components\Section::make('🥗 Dietary Preferences')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('dietary_preferences')
                                            ->label('Preferences')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),

                                        Infolists\Components\TextEntry::make('dietary_preferences_other')
                                            ->label('📝 Custom')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // PREFERENCIAS NUTRICIONALES
                                Infolists\Components\Section::make('💪 Nutritional Preferences')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('nutrition_preferences')
                                            ->label('Preferences')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),

                                        Infolists\Components\TextEntry::make('nutrition_preferences_other')
                                            ->label('📝 Custom')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // ALIMENTOS A EVITAR
                                Infolists\Components\Section::make('❌ Alimentos a Evitar')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('foods_to_avoid')
                                            ->label('Foods')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // CUISINAS FAVORITAS
                                Infolists\Components\Section::make('🍽️ Cuisinas Favoritas')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('favorite_cuisines')
                                            ->label('Cuisines')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),

                                        Infolists\Components\TextEntry::make('favorite_cuisines_other')
                                            ->label('📝 Custom')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // RESTAURANTES FAVORITOS
                                Infolists\Components\Section::make('🏪 Restaurantes Favoritos')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('favorite_restaurants')
                                            ->label('Restaurants')
                                            ->formatStateUsing(fn($state) => $this->formatJson($state))
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // PREFERENCIAS DE NIÑOS
                                Infolists\Components\Section::make('👶 Children Preferences')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('children_preferences')
                                            ->label('Preferences')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                // METADATA
                                Infolists\Components\Section::make('Registration Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn($state) => match($state) {
                                                'completado' => 'success',
                                                'pendiente' => 'warning',
                                                default => 'gray',
                                            }),

                                        Infolists\Components\TextEntry::make('created_at')
                                            ->label('Created')
                                            ->dateTime('d/m/Y H:i'),

                                        Infolists\Components\TextEntry::make('updated_at')
                                            ->label('Updated')
                                            ->dateTime('d/m/Y H:i'),
                                    ])
                                    ->columns(3)
                                    ->collapsible()
                                    ->collapsed(true),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // No mostrar formulario de edición, solo lectura
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'completado',
                        'warning' => 'pendiente',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View'),
            ])
            ->emptyStateActions([]);
    }

    /**
     * Formatea JSON a string legible
     */
    private function formatJson($data): ?string
    {
        if (empty($data)) {
            return 'N/A';
        }

        // Si ya es un array, convertir a string
        if (is_array($data)) {
            return implode(', ', array_filter($data));
        }

        // Si es JSON string, decodificar
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                return implode(', ', array_filter($decoded));
            }
        }

        return $data;
    }
}

