<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderServiceResource\Pages;
use App\Models\OrderService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderServiceResource extends Resource
{
    protected static ?string $model = OrderService::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Order Services';

    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->label('Order')
                ->relationship('order', 'order_number')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('service_number')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\TextInput::make('title')
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),

            Forms\Components\Select::make('assigned_chef_id')
                ->label('Chef')
                ->relationship('assignedChef', 'name')
                ->searchable()
                ->preload(),

            Forms\Components\DateTimePicker::make('scheduled_at')
                ->label('Scheduled At')
                ->nullable()
                ->helperText('Optional: you can schedule this later from the calendar.'),

            Forms\Components\Select::make('status')
                ->options(OrderService::statusOptions())
                ->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_number')
                    ->label('Service #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedChef.name')
                    ->label('Chef'),
                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => OrderService::statusOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(OrderService::statusOptions()),
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
            'index' => Pages\ListOrderServices::route('/'),
            'calendar' => Pages\ServiceCalendar::route('/calendar'),
            'create' => Pages\CreateOrderService::route('/create'),
            'edit' => Pages\EditOrderService::route('/{record}/edit'),
        ];
    }
}

