<?php

namespace App\Filament\Resources\TaskAplikasiResource\Pages;

use App\Filament\Resources\TaskAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskAplikasi extends EditRecord
{
    protected static string $resource = TaskAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
