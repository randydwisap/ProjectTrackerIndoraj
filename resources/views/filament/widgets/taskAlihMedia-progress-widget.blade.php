<x-filament::widget>
    <x-filament::card>
        <h2 class="text-xl font-bold">Proyek - Progress Pengerjaan</h2>
        <small class="text-xs font-regular text-gray-500 dark:text-gray-400">Alih Media<br>Bobot: 1 = 30%, 2 = 30%, 3 = 20%, 4 = 20%</small>

        @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @else
            <div id="task-list" style="height: 305px; max-height: 315px; overflow-y: auto; max-width:600px;">
                <!-- Tampilkan task -->
                @foreach ($tasks as $task)
                <div class="progress-container my-2">
                    <!-- Progress Bar (background) -->
                    <div class="progress-bar" style = "background-color: {{ $task['progress'] >= 80 ? '#4caf50' : ($task['progress'] >= 50 ? '#ffeb3b' : '#f44336') }}; width: {{ round($task['progress'], 2) }}%;">
                        <div class="progress-bar-anime">
                        <!-- Text inside progress -->
                        <div class="progress-text flex justify-between px-1 mt-5% text-white">
                            <span>{{ $task['nama'] }} - {{ round($task['progress'], 2) }}%</span>
                        </div>
                        </div>
                    </div>
                </div>
                    
<!-- Styling untuk Progress Bar -->
<style>
    .progress-container {
        width: 100%;
        padding-right: 0;
        padding-left: 0;
        background-color: #e5e7eb;
        border-radius: 0.375rem;
        height: 1.5rem;
        position: relative;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        height: 100%;
        border-radius: 0.375rem;
        background-color: green;
        width: {{ round($task['progress'], 2) }}%;
        transition: width 0.3s, background-color 0.3s;
    }

    .progress-text {
        font-size: 0.575rem;
        min-width: 300px;
        max-width: 600px;
        align-items: center;
        height: 100%;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .progress-bar-anime {
        content: '';
        display: block;
        height: 100%;
        border-radius: 0.375rem;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 25%, rgba(255, 255, 255, 0) 25%, rgba(255, 255, 255, 0) 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, rgba(255, 255, 255, 0) 75%, rgba(255, 255, 255, 0) 100%);
        background-size: 40px 40px;
        animation: progress-bar-stripes 1s linear infinite;
    }

    @keyframes progress-bar-stripes {
        from {
            background-position: 40px 0;
        }
        to {
            background-position: 0 0;
        }
    }
</style>
                @endforeach
            </div>
        @endif
    </x-filament::card>
</x-filament::widget>
