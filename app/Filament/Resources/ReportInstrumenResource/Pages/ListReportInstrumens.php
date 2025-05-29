<?php

namespace App\Filament\Resources\ReportInstrumenResource\Pages;

use App\Filament\Resources\ReportInstrumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportInstrumens extends ListRecords
{
    protected static string $resource = ReportInstrumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
