<?php

namespace App\Filament\Resources\JenisTaskAlihMediaResource\Pages;

use App\Filament\Resources\JenisTaskAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisTaskAlihMedia extends EditRecord
{
    protected static string $resource = JenisTaskAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
