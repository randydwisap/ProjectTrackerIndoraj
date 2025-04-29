<?php

namespace App\Filament\Resources\TaskWeekAlihMediaResource\Pages;

use App\Filament\Resources\TaskWeekAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskWeekAlihMedia extends ListRecords
{
    protected static string $resource = TaskWeekAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
