<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OrderServiceResource;
use App\Models\OrderService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;

class ServicesCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Services Calendar';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'services-calendar';

    protected static string $view = 'filament.pages.services-calendar';

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
            ->with('order')
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
                        return [
                            'id' => $service->id,
                            'time' => $service->scheduled_at?->format('H:i'),
                            'title' => $service->title ?: 'Service #' . $service->service_number,
                            'status' => $this->statusLabel($service->status),
                            'status_color' => $this->statusColorClass($service->status),
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

    protected function statusColorClass(string $status): string
    {
        return match ($status) {
            OrderService::STATUS_COMPLETED => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            OrderService::STATUS_IN_PROGRESS, OrderService::STATUS_IN_PREPARATION => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            OrderService::STATUS_OUT_FOR_DELIVERY, OrderService::STATUS_HOME_SERVICE => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
            default => 'border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200',
        };
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            OrderService::STATUS_PENDING_CONFIGURATION => 'Pending setup',
            OrderService::STATUS_MENU_SENT => 'Menu sent',
            OrderService::STATUS_PENDING_SELECTION => 'Waiting for selection',
            OrderService::STATUS_SELECTION_CONFIRMED => 'Selection confirmed',
            OrderService::STATUS_SCHEDULED => 'Scheduled',
            OrderService::STATUS_IN_PREPARATION => 'In preparation',
            OrderService::STATUS_IN_PROGRESS => 'In progress',
            OrderService::STATUS_OUT_FOR_DELIVERY => 'Out for delivery',
            OrderService::STATUS_HOME_SERVICE => 'Home service',
            OrderService::STATUS_COMPLETED => 'Completed',
            default => $status,
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('table')
                ->label('Table view')
                ->icon('heroicon-o-bars-3')
                ->url(OrderServiceResource::getUrl('index')),
            Action::make('create')
                ->label('Create service')
                ->icon('heroicon-o-plus')
                ->url(OrderServiceResource::getUrl('create')),
        ];
    }
}

