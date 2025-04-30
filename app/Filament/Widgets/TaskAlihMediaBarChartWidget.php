<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TaskAlihMediaBarChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Track Alih Media';
    protected static ?int $sort = 5;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $results = DB::table('task_day_alih_media')
            ->join('task_week_alih_media', 'task_day_alih_media.task_week_alih_media_id', '=', 'task_week_alih_media.id')
            ->join('task_alih_media', 'task_week_alih_media.task_alih_media_id', '=', 'task_alih_media.id')
            ->select('task_week_alih_media.nama_week as minggu', DB::raw('COUNT(DISTINCT task_week_alih_media.task_alih_media_id) as total_selesai'))
            ->where('task_day_alih_media.status', 'On Track')
            ->groupBy('task_week_alih_media.nama_week')
            ->orderBy('task_week_alih_media.nama_week')
            ->get();

        $labels = $results->pluck('minggu')->toArray();
        $data = $results->pluck('total_selesai')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Daily On Track',
                    'data' => $data,
                    'backgroundColor' => '#4caf50',
                    'borderColor' => '#388e3c',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
