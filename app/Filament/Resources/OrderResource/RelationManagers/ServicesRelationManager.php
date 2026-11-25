<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\OrderService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    protected static ?string $title = 'Services';

    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Service Details')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Basic Information')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('service_number')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->default(function ($livewire): int {
                                    $ownerRecord = method_exists($livewire, 'getOwnerRecord')
                                        ? $livewire->getOwnerRecord()
                                        : null;

                                    if (!$ownerRecord) {
                                        return 1;
                                    }

                                    return ((int) $ownerRecord->services()->max('service_number')) + 1;
                                })
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Auto-generated in sequence for this order.')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('title')
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('description')
                                ->maxLength(1000)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('assigned_chef_id')
                                ->label('Chef')
                                ->relationship(
                                    'assignedChef',
                                    'name',
                                    fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereIn('role', ['admin', 'chef'])
                                )
                                ->searchable()
                                ->preload(),

                            Forms\Components\DateTimePicker::make('scheduled_at')
                                ->label('Scheduled At')
                                ->nullable()
                                ->helperText('Optional: schedule it later from the calendar.'),

                            Forms\Components\Select::make('status')
                                ->options(OrderService::statusOptions())
                                ->required()
                                ->columnSpanFull(),
                        ]),

                    Tabs\Tab::make('Menus')
                        ->columns(2)
                        ->schema([
                            Section::make('Chef Free Menu')
                                ->description('Chef can write a free menu proposal for this service.')
                                ->schema([
                                    Forms\Components\TextInput::make('configuration.free_menu_title')
                                        ->label('Menu title')
                                        ->maxLength(255)
                                        ->columnSpanFull(),

                                    Forms\Components\MarkdownEditor::make('configuration.free_menu_markdown')
                                        ->label('Chef menu (Markdown)')
                                        ->helperText('Write the menu freely for this service. The chef can adapt it based on selected dishes.')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('Chef Notes')
                        ->columns(2)
                        ->schema([
                            Section::make('Notes for Chef')
                                ->description('Add special instructions and notes')
                                ->schema([
                                    Forms\Components\Repeater::make('chefNotes')
                                        ->relationship('chefNotes')
                                        ->schema([
                                            Forms\Components\Textarea::make('note')
                                                ->label('Note')
                                                ->maxLength(1000)
                                                ->required()
                                                ->columnSpanFull(),

                                            Forms\Components\Select::make('priority')
                                                ->options([
                                                    'low' => 'Low Priority',
                                                    'medium' => 'Medium Priority',
                                                    'high' => 'High Priority',
                                                ])
                                                ->default('medium'),

                                            Forms\Components\Toggle::make('is_private')
                                                ->label('Private Note')
                                                ->default(false),
                                        ])
                                        ->defaultItems(0)
                                        ->collapsible()
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('Grocery List')
                        ->columns(2)
                        ->schema([
                            Section::make('Shopping List')
                                ->description('Items needed for this service')
                                ->schema([
                                    Forms\Components\Group::make()
                                        ->relationship('groceryList')
                                        ->schema([
                                            Forms\Components\Textarea::make('content_markdown')
                                                ->label('Markdown Summary')
                                                ->rows(4)
                                                ->columnSpanFull(),

                                            Forms\Components\Repeater::make('items')
                                                ->relationship()
                                                ->schema([
                                                    Forms\Components\TextInput::make('item')
                                                        ->label('Item Name')
                                                        ->required()
                                                        ->columnSpan('span 2'),

                                                    Forms\Components\TextInput::make('quantity')
                                                        ->label('Quantity')
                                                        ->maxLength(255)
                                                        ->columnSpan('span 1'),

                                                    Forms\Components\TextInput::make('description')
                                                        ->label('Notes')
                                                        ->maxLength(255)
                                                        ->columnSpan('span 3'),

                                                    Forms\Components\Toggle::make('is_checked')
                                                        ->label('Checked')
                                                        ->default(false),
                                                ])
                                                ->columns(4)
                                                ->defaultItems(0)
                                                ->collapsible()
                                                ->columnSpanFull(),
                                        ])
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Tabs\Tab::make('Configuration')
                        ->columns(2)
                        ->schema([
                            Forms\Components\KeyValue::make('configuration')
                                ->label('Additional Configuration')
                                ->columnSpanFull(),
                        ]),
                ]),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('service_number')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('assignedChef.name')
                    ->label('Chef')
                    ->limit(20),
                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => OrderService::statusOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('screen')
                    ->visible(fn (): bool => $this->getOwnerRecord()->services()->count() < max(1, (int) $this->getOwnerRecord()->service_count)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Service')
                    ->modalWidth('screen'),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
