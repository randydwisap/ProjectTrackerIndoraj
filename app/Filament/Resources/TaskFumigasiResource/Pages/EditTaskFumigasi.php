<?php

namespace App\Filament\Resources\TaskFumigasiResource\Pages;

use App\Filament\Resources\TaskFumigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskFumigasi extends EditRecord
{
    protected static string $resource = TaskFumigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
