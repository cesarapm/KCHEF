<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterMenuDishResource\Pages;
use App\Models\MasterMenuDish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MasterMenuDishResource extends Resource
{
    protected static ?string $model = MasterMenuDish::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Master Menu Dishes';

    protected static ?int $navigationSort = 16;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\MarkdownEditor::make('description_markdown')
                ->label('Description (Markdown)')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('service_menu_options_count')
                    ->label('Used')
                    ->counts('serviceMenuOptions'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterMenuDishes::route('/'),
            'create' => Pages\CreateMasterMenuDish::route('/create'),
            'edit' => Pages\EditMasterMenuDish::route('/{record}/edit'),
        ];
    }
}

