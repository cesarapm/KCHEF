<?php

namespace App\Filament\Resources\OnboardingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput as NumberInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';

    protected static ?string $title = 'Document Requirements';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Requirement Information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->maxLength(1000),

                        Toggle::make('required')
                            ->label('Is Required')
                            ->default(true),

                        NumberInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),

                Section::make('File Configuration')
                    ->schema([
                        TagsInput::make('allowed_formats')
                            ->label('Allowed Formats')
                            ->placeholder('Example: pdf, doc, docx, jpg, png')
                            ->helperText('Write each format and press Enter'),

                        NumberInput::make('max_file_size')
                            ->label('Max Size (bytes)')
                            ->helperText('Example: 10485760 for 10 MB')
                            ->numeric(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        return $column->getState();
                    }),

                IconColumn::make('required')
                    ->label('Required')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('documents_count')
                    ->label('Documents')
                    ->counts('documents')
                    ->sortable(),

                TextColumn::make('allowed_formats')
                    ->label('Formats')
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $formats = $column->getState();
                        if (is_string($formats)) {
                            $formats = json_decode($formats, true);
                        }
                        if (is_array($formats)) {
                            return implode(', ', array_map('strtoupper', $formats));
                        }
                        return null;
                    }),
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
