<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\TaskAplikasi;

class AplikasiCountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tugas Aplikasi', TaskAplikasi::count())
                ->icon('heroicon-o-briefcase')
                ->color('primary'),

            Stat::make('Tugas Selesai Aplikasi', TaskAplikasi::where('status', 'Completed')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tugas Behind Schedule Aplikasi', TaskAplikasi::where('status', 'Behind Schedule')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Tugas Far Behind Schedule Aplikasi', TaskAplikasi::where('status', 'Far Behind Schedule')->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
