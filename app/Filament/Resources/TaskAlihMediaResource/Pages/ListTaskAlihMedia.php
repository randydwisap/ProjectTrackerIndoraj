<?php

namespace App\Filament\Resources\TaskAlihMediaResource\Pages;

use App\Filament\Resources\TaskAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskAlihMedia extends ListRecords
{
    protected static string $resource = TaskAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Proyek'), // Memindahkan ->label() ke baris yang sama
        ];
    }
}
