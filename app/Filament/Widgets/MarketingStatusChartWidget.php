<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Marketing;

class MarketingStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Marketing'; // Judul Widget
    protected static ?int $sort = 2; // Urutan di dashboard
    protected static ?string $maxHeight = '300px'; // Atur tinggi chart


    protected function getType(): string
    {
        return 'doughnut'; // Bisa juga 'pie'
    }

    protected function getData(): array
    {
        $completed = Marketing::where('status', 'Completed')->count();
        $onHold = Marketing::where('status', 'On Hold')->count();
        $inProgress = Marketing::where('status', ' In Progress')->count();
        $pending = Marketing::where('status', 'Pending')->count();

        return [
            'labels' => ['Completed', 'On Hold', 'In Progress', 'Pending'],
            'datasets' => [
                [
                    'label' => 'Jumlah Marketing',
                    'data' => [$completed, $onHold, $inProgress, $pending],
                    'backgroundColor' => ['#808080', '#10B981', '#F59E0B', '#EF4444'], // Warna Pie Chart
                    'hoverOffset' => 4,
                ],
            ],
        ];
    }
}
