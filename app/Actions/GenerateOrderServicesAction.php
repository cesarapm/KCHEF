<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderService;
use Illuminate\Support\Facades\DB;

class GenerateOrderServicesAction
{
    public function handle(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->payment_status !== Order::PAYMENT_CONFIRMED) {
                return;
            }

            $targetServices = max(1, (int) $lockedOrder->service_count);

            for ($serviceNumber = 1; $serviceNumber <= $targetServices; $serviceNumber++) {
                $service = OrderService::query()->firstOrCreate(
                    [
                        'order_id' => $lockedOrder->id,
                        'service_number' => $serviceNumber,
                    ],
                    [
                        'title' => "Servicio {$serviceNumber}",
                        'status' => OrderService::STATUS_PENDING_CONFIGURATION,
                    ]
                );

                if ($service->wasRecentlyCreated) {
                    $service->statusHistories()->create([
                        'from_status' => null,
                        'to_status' => $service->status,
                        'changed_by' => null,
                        'notes' => 'Servicio generado automáticamente al confirmar pago.',
                    ]);
                }
            }
        });
    }
}

