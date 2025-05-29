<?php

namespace App\Filament\Resources\TaskInstrumenResource\Pages;

use App\Filament\Resources\TaskInstrumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskInstrumen extends EditRecord
{
    protected static string $resource = TaskInstrumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
