<x-filament-panels::page>
    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Monthly Calendar
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ ucfirst($this->monthLabel()) }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-filament::button color="gray" wire:click="previousMonth" icon="heroicon-m-chevron-left">
                    Previous
                </x-filament::button>
                <x-filament::button color="gray" wire:click="currentMonth">
                    Today
                </x-filament::button>
                <x-filament::button color="gray" wire:click="nextMonth" icon="heroicon-m-chevron-right" icon-position="after">
                    Next
                </x-filament::button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
            <div class="grid min-w-[960px] grid-cols-7 border-b border-gray-200 dark:border-gray-700">
                @foreach ($this->weekdayLabels() as $label)
                    <div class="border-r border-gray-200 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-gray-600 last:border-r-0 dark:border-gray-700 dark:text-gray-300">
                        {{ $label }}
                    </div>
                @endforeach
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($this->weeks() as $week)
                    <div class="grid min-w-[960px] grid-cols-7">
                        @foreach ($week as $day)
                            <div class="min-h-40 border-r border-gray-200 p-2 last:border-r-0 dark:border-gray-700 {{ $day['is_current_month'] ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800/40' }}">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full px-2 text-xs font-semibold {{ $day['is_today'] ? 'bg-primary-600 text-white' : 'text-gray-700 dark:text-gray-200' }}">
                                        {{ $day['date']->day }}
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    @forelse ($day['services'] as $service)
                                        <a
                                            href="{{ $service['edit_url'] }}"
                                            class="block rounded-lg border border-primary-200 bg-primary-50 p-2 text-xs hover:bg-primary-100 dark:border-primary-700 dark:bg-primary-900/20 dark:hover:bg-primary-900/40"
                                        >
                                            <div class="font-semibold text-primary-700 dark:text-primary-300">
                                                {{ $service['time'] }} · {{ $service['title'] }}
                                            </div>
                                            <div class="text-[11px] text-gray-600 dark:text-gray-300">
                                                {{ $service['order_number'] ?? 'No Order' }}
                                            </div>
                                            <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                                                {{ $service['status'] }}
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                            No services
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
