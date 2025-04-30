<?php

namespace App\Filament\Resources\TaskAplikasiResource\Pages;

use App\Filament\Resources\TaskAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskAplikasis extends ListRecords
{
    protected static string $resource = TaskAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Proyek'), // Memindahkan ->label() ke baris yang sama
        ];
    }
}
