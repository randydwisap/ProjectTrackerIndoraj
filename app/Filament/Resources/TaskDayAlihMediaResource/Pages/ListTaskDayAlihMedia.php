<?php

namespace App\Filament\Resources\TaskDayAlihMediaResource\Pages;

use App\Filament\Resources\TaskDayAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskDayAlihMedia extends ListRecords
{
    protected static string $resource = TaskDayAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Laporan Harian'), // Memindahkan ->label() ke baris yang sama
        ];
    }
}
