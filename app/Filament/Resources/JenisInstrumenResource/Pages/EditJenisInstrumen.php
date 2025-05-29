<?php

namespace App\Filament\Resources\JenisInstrumenResource\Pages;

use App\Filament\Resources\JenisInstrumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisInstrumen extends EditRecord
{
    protected static string $resource = JenisInstrumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
