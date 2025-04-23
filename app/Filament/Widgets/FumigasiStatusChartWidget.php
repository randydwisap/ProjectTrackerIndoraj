<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TaskFumigasi;

class FumigasiStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Fumigasi'; // Judul Widget
    protected static ?int $sort = 3; // Urutan di dashboard
    protected static ?string $maxHeight = '300px'; // Atur tinggi chart


    protected function getType(): string
    {
        return 'doughnut'; // Bisa juga 'pie'
    }

    protected function getData(): array
    {
        $completed = TaskFumigasi::where('status', 'Completed')->count();
        $behindSchedule = TaskFumigasi::where('status', 'Behind Schedule')->count();
        $farBehindSchedule = TaskFumigasi::where('status', 'Far Behind Schedule')->count();

        return [
            'labels' => ['Completed', 'Behind Schedule', 'Far Behind Schedule'],
            'datasets' => [
                [
                    'label' => 'Jumlah Tugas',
                    'data' => [$completed, $behindSchedule, $farBehindSchedule],
                    'backgroundColor' => ['#10B981', '#F59E0B', '#EF4444'], // Warna Pie Chart
                    'hoverOffset' => 4,
                ],
            ],
        ];
    }
}
