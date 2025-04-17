<?php

namespace App\Filament\Resources\TaskFumigasiResource\Pages;

use App\Filament\Resources\TaskFumigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskFumigasis extends ListRecords
{
    protected static string $resource = TaskFumigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
