<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Task; // Pastikan untuk mengimport model Task
use Illuminate\Contracts\View\View;

class TaskProgressWidget extends Widget
{
    protected static ?string $heading = 'Proyek - Progress Pengerjaan';

    public function render(): View
    {
        // Ambil semua task dari database
        $tasks = Task::all();

        // Cek apakah ada task yang ditemukan
        if ($tasks->isEmpty()) {
            return view('filament.widgets.task-progress-widget', [
                'error' => 'Tidak ada task yang ditemukan',
            ]);
        }

        // Menghitung progress untuk masing-masing task
$tasksProgress = $tasks->map(function ($task) {

    $step1Progress = $task->volume_arsip > 0 
        ? ($task->dikerjakan_step1 / $task->volume_arsip) * 100 
        : 0;

    $step2Progress = $task->hasil_pemilahan > 0 
        ? ($task->dikerjakan_step2 / $task->hasil_pemilahan) * 100 
        : 0;

    $step3Progress = $task->hasil_pemilahan > 0 
        ? ($task->dikerjakan_step3 / $task->hasil_pemilahan) * 100 
        : 0;

    $step4Progress = $task->hasil_pemilahan > 0 
        ? ($task->dikerjakan_step4 / $task->hasil_pemilahan) * 100 
        : 0;

            // Kalkulasi total progress
            $totalProgress = ($step1Progress / 100 * 30) + ($step2Progress / 100 * 30) + ($step3Progress / 100 * 20) + ($step4Progress / 100 * 20);

            return [
                'nama' => $task->pekerjaan, // Nama task
                'progress' => $totalProgress, // Progress
            ];
        });

        // Kembalikan view dengan data progress task
        return view('filament.widgets.task-progress-widget', [
            'tasks' => $tasksProgress,
        ]);
    }
}
