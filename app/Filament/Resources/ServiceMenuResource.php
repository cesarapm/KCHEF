<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceMenuResource\Pages;
use App\Filament\Resources\ServiceMenuResource\RelationManagers\OptionsRelationManager;
use App\Models\ServiceMenu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceMenuResource extends Resource
{
    protected static ?string $model = ServiceMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Service Menus';

    protected static ?int $navigationSort = 15;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_service_id')
                ->label('Service')
                ->relationship('service', 'title')
                ->getOptionLabelFromRecordUsing(fn ($record) => ($record->title ?: "Servicio {$record->service_number}") . " ({$record->order->order_number})")
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),

            Forms\Components\DatePicker::make('menu_month')
                ->label('Menu Month'),

            Forms\Components\Textarea::make('additional_info')
                ->label('Additional Info')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('min_selection_percent')
                ->label('Min Selection %')
                ->numeric()
                ->default(70)
                ->minValue(1)
                ->maxValue(100),

            Forms\Components\Toggle::make('is_active')
                ->default(true),

            Forms\Components\Toggle::make('is_sent')
                ->label('Menu Sent'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.service_number')
                    ->label('Service #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('menu_month')
                    ->date('m/Y')
                    ->label('Month'),
                Tables\Columns\TextColumn::make('min_selection_percent')
                    ->label('Min %'),
                Tables\Columns\IconColumn::make('is_sent')
                    ->label('Sent')
                    ->boolean(),
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

    public static function getRelations(): array
    {
        return [
            OptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceMenus::route('/'),
            'create' => Pages\CreateServiceMenu::route('/create'),
            'edit' => Pages\EditServiceMenu::route('/{record}/edit'),
        ];
    }
}

