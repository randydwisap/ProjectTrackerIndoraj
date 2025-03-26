<?php

namespace App\Filament\Resources\TaskDayDetailResource\Pages;

use App\Filament\Resources\TaskDayDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskDayDetails extends ListRecords
{
    protected static string $resource = TaskDayDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
