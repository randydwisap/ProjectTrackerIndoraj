<?php

namespace App\Filament\Resources\ReportFumigasiResource\Pages;

use App\Filament\Resources\ReportFumigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportFumigasi extends EditRecord
{
    protected static string $resource = ReportFumigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
