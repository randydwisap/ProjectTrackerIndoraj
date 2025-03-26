<?php

namespace App\Filament\Resources\TaskDetailResource\Pages;

use App\Filament\Resources\TaskDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskDetail extends EditRecord
{
    protected static string $resource = TaskDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
