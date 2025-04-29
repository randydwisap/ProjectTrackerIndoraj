<?php

namespace App\Filament\Resources\TaskDayAlihMediaResource\Pages;

use App\Filament\Resources\TaskDayAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskDayAlihMedia extends EditRecord
{
    protected static string $resource = TaskDayAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
