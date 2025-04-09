<?php

namespace App\Filament\Resources\ReportAplikasiResource\Pages;

use App\Filament\Resources\ReportAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportAplikasis extends ListRecords
{
    protected static string $resource = ReportAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
