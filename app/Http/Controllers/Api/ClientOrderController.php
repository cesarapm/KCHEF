<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ClientOrderController extends Controller
{
    private const DEFAULT_MIN_SELECTION_PERCENT = 70;

    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->whereHas('orderDishes')
            ->with([
                'orderDishes.masterDish:id,title,description_markdown,is_active',
                'services' => fn ($query) => $query
                    ->with([
                        'assignedChef:id,name',
                        'chefNotes' => fn ($noteQuery) => $noteQuery->where('is_private', false)->latest('id'),
                        'groceryList.items',
                    ])
                    ->orderBy('service_number'),
            ])
            ->latest('id')
            ->get()
            ->map(function (Order $order): array {
                $minPercent = (int) ($order->metadata['min_selection_percent'] ?? self::DEFAULT_MIN_SELECTION_PERCENT);
                $minPercent = max(1, min(100, $minPercent));

                $totalDishes = $order->orderDishes->count();
                $selectedCount = $order->orderDishes->where('is_selected_by_client', true)->count();
                $requiredCount = (int) ceil($totalDishes * ($minPercent / 100));
                $meetsMinimum = $totalDishes > 0 && $selectedCount >= $requiredCount;

                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'paid_at' => optional($order->paid_at)?->toIso8601String(),
                    'selection' => [
                        'min_percent' => $minPercent,
                        'required_count' => $requiredCount,
                        'selected_count' => $selectedCount,
                        'total_dishes' => $totalDishes,
                        'meets_minimum' => $meetsMinimum,
                        'confirmed_at' => $order->metadata['client_menu_confirmed_at'] ?? null,
                    ],
                    'dishes' => $order->orderDishes->map(fn ($orderDish): array => [
                        'id' => $orderDish->id,
                        'master_menu_dish_id' => $orderDish->master_menu_dish_id,
                        'title' => $orderDish->masterDish?->title,
                        'description_markdown' => $orderDish->masterDish?->description_markdown,
                        'is_selected_by_client' => (bool) $orderDish->is_selected_by_client,
                        'selected_at' => optional($orderDish->selected_at)?->toIso8601String(),
                    ])->values(),
                    'services' => $order->services->map(fn ($service): array => [
                        'id' => $service->id,
                        'service_number' => $service->service_number,
                        'title' => $service->title,
                        'description' => $service->description,
                        'status' => $service->status,
                        'scheduled_at' => optional($service->scheduled_at)?->toIso8601String(),
                        'chef_name' => $service->assignedChef?->name,
                        'free_menu_title' => data_get($service->configuration, 'free_menu_title'),
                        'free_menu_markdown' => data_get($service->configuration, 'free_menu_markdown'),
                        'chef_notes' => $service->chefNotes->map(fn ($note): array => [
                            'id' => $note->id,
                            'note' => $note->note,
                            'priority' => $note->priority,
                        ])->values(),
                        'grocery_list' => $service->groceryList ? [
                            'content_markdown' => $service->groceryList->content_markdown,
                            'items' => $service->groceryList->items->map(fn ($item): array => [
                                'id' => $item->id,
                                'item' => $item->item,
                                'quantity' => $item->quantity,
                                'description' => $item->description,
                                'is_checked' => (bool) $item->is_checked,
                            ])->values(),
                        ] : null,
                    ])->values(),
                ];
            })
            ->values();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function updateSelections(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        $selectedIds = $this->validateSelectionPayload($request, $order, required: true);
        $this->syncSelections($order, $selectedIds);

        return response()->json([
            'message' => 'Dish selections updated.',
        ]);
    }

    public function confirmSelections(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        if ($request->has('selected_order_dish_ids')) {
            $selectedIds = $this->validateSelectionPayload($request, $order, required: false);
            $this->syncSelections($order, $selectedIds);
        }

        $minPercent = (int) ($order->metadata['min_selection_percent'] ?? self::DEFAULT_MIN_SELECTION_PERCENT);
        $minPercent = max(1, min(100, $minPercent));

        $totalDishes = $order->orderDishes()->count();
        $selectedCount = $order->orderDishes()->where('is_selected_by_client', true)->count();
        $requiredCount = (int) ceil($totalDishes * ($minPercent / 100));

        if ($totalDishes === 0) {
            throw ValidationException::withMessages([
                'order' => 'This order does not have dishes assigned yet.',
            ]);
        }

        if ($selectedCount < $requiredCount) {
            throw ValidationException::withMessages([
                'selected_order_dish_ids' => "You must select at least {$requiredCount} dishes ({$minPercent}%).",
            ]);
        }

        $metadata = $order->metadata ?? [];
        $metadata['client_menu_confirmed_at'] = now()->toIso8601String();
        $order->update(['metadata' => $metadata]);

        return response()->json([
            'message' => 'Selections confirmed successfully.',
            'confirmed_at' => $metadata['client_menu_confirmed_at'],
        ]);
    }

    public function showService(Request $request, OrderService $service)
    {
        $order = $service->order()->firstOrFail();
        $this->authorizeOrder($request, $order);

        $service->load([
            'assignedChef:id,name',
            'chefNotes' => fn ($query) => $query->where('is_private', false)->latest('id'),
            'groceryList.items',
        ]);

        return response()->json([
            'service' => [
                'id' => $service->id,
                'order_id' => $service->order_id,
                'order_number' => $order->order_number,
                'service_number' => $service->service_number,
                'title' => $service->title,
                'description' => $service->description,
                'status' => $service->status,
                'scheduled_at' => optional($service->scheduled_at)?->toIso8601String(),
                'chef_name' => $service->assignedChef?->name,
                'free_menu_title' => data_get($service->configuration, 'free_menu_title'),
                'free_menu_markdown' => data_get($service->configuration, 'free_menu_markdown'),
                'chef_notes' => $service->chefNotes->map(fn ($note): array => [
                    'id' => $note->id,
                    'note' => $note->note,
                    'priority' => $note->priority,
                ])->values(),
                'grocery_list' => $service->groceryList ? [
                    'content_markdown' => $service->groceryList->content_markdown,
                    'items' => $service->groceryList->items->map(fn ($item): array => [
                        'id' => $item->id,
                        'item' => $item->item,
                        'quantity' => $item->quantity,
                        'description' => $item->description,
                        'is_checked' => (bool) $item->is_checked,
                    ])->values(),
                ] : null,
            ],
        ]);
    }

    private function validateSelectionPayload(Request $request, Order $order, bool $required): Collection
    {
        $rules = [
            'selected_order_dish_ids' => [$required ? 'required' : 'nullable', 'array'],
            'selected_order_dish_ids.*' => ['integer'],
        ];

        $validated = $request->validate($rules);

        $selectedIds = collect($validated['selected_order_dish_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $orderDishIds = $order->orderDishes()->pluck('id');
        $invalidIds = $selectedIds->diff($orderDishIds);

        if ($invalidIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'selected_order_dish_ids' => 'Some selected dishes do not belong to this order.',
            ]);
        }

        return $selectedIds;
    }

    private function syncSelections(Order $order, Collection $selectedIds): void
    {
        $order->orderDishes()->update([
            'is_selected_by_client' => false,
            'selected_at' => null,
        ]);

        if ($selectedIds->isNotEmpty()) {
            $order->orderDishes()
                ->whereIn('id', $selectedIds)
                ->update([
                    'is_selected_by_client' => true,
                    'selected_at' => now(),
                ]);
        }
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        if ((int) $order->user_id !== (int) $request->user()->id) {
            abort(403, 'You are not allowed to modify this order.');
        }
    }
}

