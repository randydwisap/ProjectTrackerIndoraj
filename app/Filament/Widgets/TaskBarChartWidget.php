<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TaskBarChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Track Pengolahan Arsip';
    protected static ?int $sort = 5;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        // Query sesuai dengan yang kamu berikan
        $results = DB::table('task_day_details')
            ->join('task_week_overviews', 'task_day_details.task_week_overview_id', '=', 'task_week_overviews.id')
            ->join('tasks', 'task_week_overviews.task_id', '=', 'tasks.id')
            ->select('task_week_overviews.nama_week as minggu', DB::raw('COUNT(DISTINCT task_week_overviews.task_id) as total_selesai'))
            ->where('task_day_details.status', 'On Track')
            ->groupBy('task_week_overviews.nama_week')
            ->orderBy('task_week_overviews.nama_week')
            ->get();

        // Ubah hasil query menjadi array untuk chart
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
