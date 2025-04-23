<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Task;

class TaskCountWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tugas Pengolahan Arsip', Task::count())
                ->icon('heroicon-o-briefcase')
                ->color('primary'),

            Stat::make('Tugas Selesai Pengolahan Arsip', Task::where('status', 'Completed')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tugas Behind Schedule Pengolahan Arsip', Task::where('status', 'Behind Schedule')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Tugas Far Behind Schedule Pengolahan Arsip', Task::where('status', 'Far Behind Schedule')->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
