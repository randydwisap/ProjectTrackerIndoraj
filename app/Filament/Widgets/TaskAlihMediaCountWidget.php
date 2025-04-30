<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\TaskAlihMedia;

class TaskAlihMediaCountWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tugas Alih Media', TaskAlihMedia::count())
                ->icon('heroicon-o-briefcase')
                ->color('primary'),

            Stat::make('Tugas Selesai Alih Media', TaskAlihMedia::where('status', 'Completed')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tugas Behind Schedule Alih Media', TaskAlihMedia::where('status', 'Behind Schedule')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Tugas Far Behind Schedule Alih Media', TaskAlihMedia::where('status', 'Far Behind Schedule')->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
