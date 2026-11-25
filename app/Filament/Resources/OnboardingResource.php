<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OnboardingResource\Pages;
use App\Filament\Resources\OnboardingResource\RelationManagers;
use App\Models\Onboarding;
use App\Models\Order;
use App\Models\Plan;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OnboardingResource extends Resource
{
    protected static ?string $model = Onboarding::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Onboarding';

    protected static ?string $modelLabel = 'Onboarding';

    protected static ?string $pluralModelLabel = 'Onboardings';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Onboarding Information')
                    ->description('Details of the onboarding process')
                    ->schema([
                        Select::make('prospect_id')
                            ->label('Prospect')
                            ->relationship('prospect', 'full_name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->unique('onboardings', 'prospect_id', ignoreRecord: true),

                        Select::make('user_id')
                            ->label('Assigned Supervisor')
                            ->relationship('user', 'name', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereIn('role', ['admin', 'chef']))
                            ->searchable()
                            ->preload()
                            ->placeholder('Select user'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'configuracion_pendiente' => 'Configuration Pending',
                                'registro_pendiente' => 'Registration Pending',
                                'documentos_pendientes' => 'Documents Pending',
                                'en_revision' => 'Under Review',
                                'requiere_correcciones' => 'Requires Corrections',
                                'completado' => 'Completed',
                            ])
                            ->required(),

                        Select::make('plan_id')
                            ->label('Selected Plan')
                            ->relationship('plan', 'title')
                            ->getOptionLabelFromRecordUsing(fn (Plan $record) => "{$record->title} - \${$record->cost}")
                            ->searchable()
                            ->preload()
                            ->placeholder('Select plan'),
                    ])->columns(2),

                Section::make('Dates')
                    ->description('Timeline of the process')
                    ->schema([
                        DateTimePicker::make('started_at')
                            ->label('Started')
                            ->displayFormat('d/m/Y H:i'),

                        DateTimePicker::make('completed_at')
                            ->label('Completed')
                            ->displayFormat('d/m/Y H:i'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prospect.full_name')
                    ->label('Prospect')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('prospect.email')
                    ->label('Email')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'configuracion_pendiente',
                        'info' => 'registro_pendiente',
                        'warning' => 'documentos_pendientes',
                        'info' => 'en_revision',
                        'danger' => 'requiere_correcciones',
                        'success' => 'completado',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match($state) {
                            'configuracion_pendiente' => 'Config. Pending',
                            'registro_pendiente' => 'Registration Pending',
                            'documentos_pendientes' => 'Docs Pending',
                            'en_revision' => 'Under Review',
                            'requiere_correcciones' => 'Requires Corrections',
                            'completado' => 'Completed',
                            default => $state
                        };
                    })
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Supervisor')
                    ->sortable(),

                TextColumn::make('plan.title')
                    ->label('Plan')
                    ->sortable(),

                TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('requirements_count')
                    ->label('Requirements')
                    ->counts('requirements'),

                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'configuracion_pendiente' => 'Configuration Pending',
                        'registro_pendiente' => 'Registration Pending',
                        'documentos_pendientes' => 'Documents Pending',
                        'en_revision' => 'Under Review',
                        'requiere_correcciones' => 'Requires Corrections',
                        'completado' => 'Completed',
                    ]),
                SelectFilter::make('prospect.status')
                    ->label('Prospect Status')
                    ->options([
                        'pendiente' => 'Pending',
                        'aprobado' => 'Approved',
                        'enlace_enviado' => 'Link Sent',
                        'cliente_registrado' => 'Client Registered',
                        'en_onboarding' => 'In Onboarding',
                        'completado' => 'Completed',
                        'rechazado' => 'Rejected',
                    ])
                    ->attribute('prospect.status'),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('prospect', fn (Builder $q) => $q->whereIn('status', ['aprobado', 'enlace_enviado', 'cliente_registrado', 'en_onboarding', 'completado'])))
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('create_order')
                    ->label('Create Order')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('success')
                    ->action(function (Onboarding $record) {
                        $prospect = $record->prospect;
                        $userId = $prospect?->user_id;

                        if (!$userId) {
                            Notification::make()
                                ->title('This onboarding does not have a registered client yet.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $order = Order::query()->create([
                            'onboarding_id' => $record->id,
                            'user_id' => $userId,
                            'plan_id' => $record->plan_id,
                            'payment_status' => Order::PAYMENT_PENDING,
                            'service_count' => 4,
                        ]);

                        redirect(OrderResource::getUrl('edit', ['record' => $order]));
                    }),
                Tables\Actions\Action::make('open_latest_order')
                    ->label('Open Last Order')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->visible(fn (Onboarding $record): bool => $record->orders()->exists())
                    ->action(function (Onboarding $record) {
                        $order = $record->orders()->latest('id')->first();

                        if (!$order) {
                            Notification::make()
                                ->title('No orders found for this onboarding yet.')
                                ->warning()
                                ->send();

                            return;
                        }

                        redirect(OrderResource::getUrl('edit', ['record' => $order]));
                    }),
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
            RelationManagers\FormRelationManager::class,
            // RelationManagers\RequirementsRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOnboardings::route('/'),
            'create' => Pages\CreateOnboarding::route('/create'),
            'view' => Pages\ViewOnboarding::route('/{record}'),
            'edit' => Pages\EditOnboarding::route('/{record}/edit'),
        ];
    }
}
