<?php

namespace App\Filament\Resources\TaskAlihMediaResource\Pages;

use App\Filament\Resources\TaskAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskAlihMedia extends EditRecord
{
    protected static string $resource = TaskAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
