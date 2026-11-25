<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\MasterMenuDish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderDishesRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDishes';

    protected static ?string $title = 'Order Dishes';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('master_menu_dish_id')
                ->label('Dish from master menu')
                ->relationship('masterDish', 'title')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Toggle::make('is_selected_by_client')
                ->label('Selected by client')
                ->default(false),

            Forms\Components\DateTimePicker::make('selected_at')
                ->label('Selected at')
                ->disabled()
                ->dehydrated(false),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('masterDish.title')
            ->columns([
                Tables\Columns\TextColumn::make('masterDish.title')
                    ->label('Dish')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('masterDish.description_markdown')
                    ->label('Description')
                    ->limit(80),
                Tables\Columns\IconColumn::make('is_selected_by_client')
                    ->label('Client selected')
                    ->boolean(),
                Tables\Columns\TextColumn::make('selected_at')
                    ->label('Selected at')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('add_multiple_dishes')
                    ->label('Add from master (checkboxes)')
                    ->icon('heroicon-o-list-bullet')
                    ->modalHeading('Add dishes from master menu')
                    ->form([
                        Forms\Components\CheckboxList::make('dish_ids')
                            ->label('Master menu dishes')
                            ->options(fn (): array => $this->getAvailableMasterDishOptions())
                            ->columns(2)
                            ->required()
                            ->bulkToggleable()
                            ->searchable(),
                    ])
                    ->action(function (array $data): void {
                        $dishIds = $data['dish_ids'] ?? [];
                        if (empty($dishIds)) {
                            Notification::make()
                                ->title('Please select at least one dish.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $created = 0;
                        foreach ($dishIds as $dishId) {
                            $orderDish = $this->getOwnerRecord()
                                ->orderDishes()
                                ->firstOrCreate(
                                    ['master_menu_dish_id' => $dishId],
                                    ['is_selected_by_client' => false]
                                );

                            if ($orderDish->wasRecentlyCreated) {
                                $created++;
                            }
                        }

                        Notification::make()
                            ->title("Added {$created} dish(es) to this order.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    protected function getAvailableMasterDishOptions(): array
    {
        $usedDishIds = $this->getOwnerRecord()
            ->orderDishes()
            ->pluck('master_menu_dish_id');

        return MasterMenuDish::query()
            ->where('is_active', true)
            ->whereNotIn('id', $usedDishIds)
            ->orderBy('title')
            ->pluck('title', 'id')
            ->all();
    }
}

