<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\TaskFumigasi;

class FumigasiCountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tugas Fumigasi', TaskFumigasi::count())
                ->icon('heroicon-o-briefcase')
                ->color('primary'),

            Stat::make('Tugas Selesai Fumigasi', TaskFumigasi::where('status', 'Completed')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tugas Behind Schedule Fumigasi', TaskFumigasi::where('status', 'Behind Schedule')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Tugas Far Behind Schedule Fumigasi', TaskFumigasi::where('status', 'Far Behind Schedule')->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
