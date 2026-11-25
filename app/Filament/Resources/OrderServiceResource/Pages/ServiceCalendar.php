<?php

namespace App\Filament\Resources\OrderServiceResource\Pages;

use App\Filament\Resources\OrderServiceResource;
use App\Models\OrderService;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\Page;

class ServiceCalendar extends Page
{
    protected static string $resource = OrderServiceResource::class;

    protected static string $view = 'filament.resources.order-service-resource.pages.service-calendar';

    public string $month = '';

    public function mount(): void
    {
        $this->month = now()->startOfMonth()->format('Y-m');
    }

    public function previousMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)
            ->subMonth()
            ->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)
            ->addMonth()
            ->format('Y-m');
    }

    public function currentMonth(): void
    {
        $this->month = now()->startOfMonth()->format('Y-m');
    }

    public function monthLabel(): string
    {
        return Carbon::createFromFormat('Y-m', $this->month)
            ->locale('en')
            ->translatedFormat('F Y');
    }

    public function weekdayLabels(): array
    {
        return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    }

    public function weeks(): array
    {
        $monthStart = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $services = OrderService::query()
            ->whereNotNull('scheduled_at')
            ->whereBetween('scheduled_at', [$gridStart->copy()->startOfDay(), $gridEnd->copy()->endOfDay()])
            ->with(['order'])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(fn (OrderService $service): string => $service->scheduled_at?->toDateString() ?? '');

        $weeks = [];
        $cursor = $gridStart->copy();

        while ($cursor->lte($gridEnd)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $cursor->toDateString();
                $dayServices = ($services[$dateKey] ?? collect())
                    ->map(function (OrderService $service): array {
                        $statusLabel = OrderService::statusOptions()[$service->status] ?? $service->status;

                        return [
                            'id' => $service->id,
                            'time' => $service->scheduled_at?->format('H:i'),
                            'title' => $service->title ?: 'Service #' . $service->service_number,
                            'status' => $statusLabel,
                            'order_number' => $service->order?->order_number,
                            'edit_url' => OrderServiceResource::getUrl('edit', ['record' => $service]),
                        ];
                    })
                    ->all();

                $week[] = [
                    'date' => $cursor->copy(),
                    'is_current_month' => $cursor->month === $monthStart->month,
                    'is_today' => $cursor->isToday(),
                    'services' => $dayServices,
                ];

                $cursor->addDay();
            }

            $weeks[] = $week;
        }

        return $weeks;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('list')
                ->label('Table View')
                ->icon('heroicon-o-bars-3')
                ->url(OrderServiceResource::getUrl('index')),
            Actions\Action::make('create')
                ->label('Create Service')
                ->icon('heroicon-o-plus')
                ->url(OrderServiceResource::getUrl('create')),
        ];
    }
}

