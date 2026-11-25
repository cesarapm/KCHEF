<?php

namespace App\Filament\Resources\OnboardingResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OnboardingResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOnboarding extends ViewRecord
{
    protected static string $resource = OnboardingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_order')
                ->label('Create Order')
                ->icon('heroicon-o-shopping-bag')
                ->color('success')
                ->action(function () {
                    $prospect = $this->record->prospect;
                    $userId = $prospect?->user_id;

                    if (!$userId) {
                        Notification::make()
                            ->title('This onboarding does not have a registered client yet.')
                            ->warning()
                            ->send();

                        return;
                    }

                    $order = Order::query()->create([
                        'onboarding_id' => $this->record->id,
                        'user_id' => $userId,
                        'plan_id' => $this->record->plan_id,
                        'payment_status' => Order::PAYMENT_PENDING,
                        'service_count' => 4,
                    ]);

                    $this->redirect(OrderResource::getUrl('edit', ['record' => $order]));
                }),
            Action::make('open_latest_order')
                ->label('Open Last Order')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->visible(fn (): bool => $this->record->orders()->exists())
                ->action(function () {
                    $order = $this->record->orders()->latest('id')->first();

                    if (!$order) {
                        Notification::make()
                        ->title('No orders found for this onboarding yet.')
                        ->warning()
                        ->send();

                        return;
                    }

                    $this->redirect(OrderResource::getUrl('edit', ['record' => $order]));
                }),
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
