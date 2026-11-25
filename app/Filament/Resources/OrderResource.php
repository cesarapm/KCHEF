<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDishesRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\ServicesRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('order_number')
                ->label('Order Number')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\Select::make('user_id')
                ->label('Client')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('plan_id')
                ->label('Plan')
                ->relationship('plan', 'title')
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('onboarding_id')
                ->label('Onboarding')
                ->relationship('onboarding', 'id')
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('payment_status')
                ->label('Payment Status')
                ->options(Order::paymentStatusOptions())
                ->required(),

            Forms\Components\TextInput::make('payment_reference')
                ->label('Payment Reference')
                ->maxLength(255),

            Forms\Components\DatePicker::make('paid_at')
                ->label('Paid At'),

            Forms\Components\TextInput::make('service_count')
                ->label('Services to Generate')
                ->numeric()
                ->default(4)
                ->minValue(1)
                ->maxValue(12),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('plan.title')
                    ->label('Plan')
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->formatStateUsing(fn (string $state): string => Order::paymentStatusOptions()[$state] ?? $state)
                    ->colors([
                        'warning' => Order::PAYMENT_PENDING,
                        'success' => Order::PAYMENT_CONFIRMED,
                        'danger' => [Order::PAYMENT_FAILED, Order::PAYMENT_REFUNDED],
                    ]),

                Tables\Columns\TextColumn::make('services_count')
                    ->label('Services')
                    ->counts('services'),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options(Order::paymentStatusOptions()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            OrderDishesRelationManager::class,
            ServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

