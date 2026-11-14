<?php

namespace App\Filament\Widgets;

use App\Models\Prospect;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProspectsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Prospects';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Prospect::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('city')
                    ->label('City')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobado',
                        'info' => 'enlace_enviado',
                        'warning' => 'cliente_registrado',
                        'info' => 'en_onboarding',
                        'success' => 'completado',
                        'danger' => 'rechazado',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match($state) {
                            'pendiente' => 'Pending',
                            'aprobado' => 'Approved',
                            'enlace_enviado' => 'Link Sent',
                            'cliente_registrado' => 'Client Registered',
                            'en_onboarding' => 'In Onboarding',
                            'completado' => 'Completed',
                            'rechazado' => 'Rejected',
                            default => $state
                        };
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Captured')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false)
            ->striped();
    }
}
