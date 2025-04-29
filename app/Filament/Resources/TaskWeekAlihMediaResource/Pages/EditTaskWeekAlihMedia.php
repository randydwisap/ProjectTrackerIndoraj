<?php

namespace App\Filament\Resources\TaskWeekAlihMediaResource\Pages;

use App\Filament\Resources\TaskWeekAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskWeekAlihMedia extends EditRecord
{
    protected static string $resource = TaskWeekAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
