<?php

namespace App\Filament\Resources\ReportAplikasiResource\Pages;

use App\Filament\Resources\ReportAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportAplikasi extends EditRecord
{
    protected static string $resource = ReportAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
