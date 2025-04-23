<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskProgressWidget extends Widget
{
    protected static string $view = 'filament.widgets.task-progress-widget';

    protected function getViewData(): array
    {
        $tasks = Task::with('taskWeekOverviews.taskDayDetails')
            ->get()
            ->map(function ($task) {
                $totalDuration = Carbon::parse($task->tgl_mulai)->diffInDays($task->tgl_selesai);
                $actualFinishedDays = $task->taskWeekOverviews
                    ->flatMap->taskDayDetails
                    ->where('status', 'On Track')
                    ->count();

                $progress = $totalDuration > 0
                    ? round(($actualFinishedDays / $totalDuration) * 100)
                    : 0;

                return [
                    'nama' => $task->pekerjaan,
                    'progress' => min($progress, 100),
                    'status' => $task->status,
                ];
            });

        return ['tasks' => $tasks];
    }
}
