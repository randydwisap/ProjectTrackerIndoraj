<?php

namespace App\Filament\Resources\ReportInstrumenResource\Pages;

use App\Filament\Resources\ReportInstrumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportInstrumen extends EditRecord
{
    protected static string $resource = ReportInstrumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
