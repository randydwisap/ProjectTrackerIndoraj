<?php

namespace App\Filament\Resources\JenisInstrumenResource\Pages;

use App\Filament\Resources\JenisInstrumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisInstrumens extends ListRecords
{
    protected static string $resource = JenisInstrumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
