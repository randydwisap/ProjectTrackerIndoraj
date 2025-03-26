<?php

namespace App\Filament\Resources\TaskDayDetailResource\Pages;

use App\Filament\Resources\TaskDayDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskDayDetail extends EditRecord
{
    protected static string $resource = TaskDayDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
