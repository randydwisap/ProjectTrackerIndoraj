<?php

namespace App\Filament\Resources\TaskInstrumenResource\Pages;

use App\Filament\Resources\TaskInstrumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskInstrumens extends ListRecords
{
    protected static string $resource = TaskInstrumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
