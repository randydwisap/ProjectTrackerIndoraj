<?php

namespace App\Filament\Resources\JenisTaskResource\Pages;

use App\Filament\Resources\JenisTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisTask extends EditRecord
{
    protected static string $resource = JenisTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
