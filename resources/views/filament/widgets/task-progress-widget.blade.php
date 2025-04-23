<x-filament::widget>
    <x-filament::card>
        <h2 class="text-xl font-bold mb-0.5">Pengolahan Arsip</h2>
        <small class="text-sm font-regular text-gray-500 dark:text-gray-400">Proses Harian On Track Berdasarkan Tanggal</small>

        @foreach ($tasks as $task)
            <div class="mb-4">
                <div class="flex justify-between mb-1">
                    <span class="font-medium">{{ $task['nama'] }}</span>
                    <span>{{ $task['progress'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-300"
                         style="
                             width: {{ $task['progress'] }}%;
                             background-color: 
                                {{ $task['progress'] >= 80 ? '#4caf50' : ($task['progress'] >= 50 ? '#ffeb3b' : '#f44336') }};
                         ">
                    </div>
                </div>
            </div>
        @endforeach
    </x-filament::card>
</x-filament::widget>
