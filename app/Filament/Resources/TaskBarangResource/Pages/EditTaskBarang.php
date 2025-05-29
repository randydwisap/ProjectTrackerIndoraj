<?php

namespace App\Filament\Resources\TaskBarangResource\Pages;

use App\Filament\Resources\TaskBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskBarang extends EditRecord
{
    protected static string $resource = TaskBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
