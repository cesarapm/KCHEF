<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextArea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Plans';

    protected static ?string $modelLabel = 'Plan';

    protected static ?string $pluralModelLabel = 'Plans';

    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Plan Information')
                    ->description('Basic plan details')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('cost')
                            ->label('Cost')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        MarkdownEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->helperText('Describe the details and features of the plan'),

                        TagsInput::make('includes')
                            ->label('Includes')
                            ->helperText('Add the elements included in the plan (e.g., Consulting, 24/7 Support)')
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->default(0),

                        Toggle::make('active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cost')
                    ->label('Cost')
                    ->money('USD')
                    ->sortable(),

                BadgeColumn::make('active')
                    ->label('Status')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
