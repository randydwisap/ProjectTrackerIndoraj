<?php

namespace App\Filament\Resources\TaskDetailResource\Pages;

use App\Filament\Resources\TaskDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskDetails extends ListRecords
{
    protected static string $resource = TaskDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Laporan Mingguan'), // Memindahkan ->label() ke baris yang sama
        ];
    }
}
