<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Aktivitas Terbaru
        </x-slot>

        <div class="space-y-4">
            @forelse ($this->getActivities() as $activity)
                <div class="flex items-start gap-4">
                    <div class="rounded-full bg-{{ $activity['color'] }}-100 p-2 text-{{ $activity['color'] }}-600">
                        <x-filament::icon
                            :icon="$activity['icon']"
                            class="h-5 w-5"
                        />
                    </div>
                    
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            <a href="{{ $activity['url'] }}" class="hover:underline">
                                {{ $activity['title'] }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $activity['description'] }}
                        </p>
                    </div>
                    
                    <div class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="text-center text-sm text-gray-500 py-4">
                    Belum ada aktivitas terbaru.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
