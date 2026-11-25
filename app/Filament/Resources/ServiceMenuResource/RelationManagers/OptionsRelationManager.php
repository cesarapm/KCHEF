<?php

namespace App\Filament\Resources\ServiceMenuResource\RelationManagers;

use App\Models\MasterMenuDish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    protected static ?string $title = 'Menu Options';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('master_menu_dish_id')
                ->label('Master dish')
                ->options(fn (): array => MasterMenuDish::query()
                    ->where('is_active', true)
                    ->orderBy('title')
                    ->pluck('title', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, callable $set): void {
                    if (!$state) {
                        return;
                    }

                    $dish = MasterMenuDish::query()->find($state);
                    if (!$dish) {
                        return;
                    }

                    $set('name', $dish->title);
                    $set('description', $dish->description_markdown);
                }),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label('Description (Markdown allowed)')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('masterDish.title')
                    ->label('Master dish')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}

