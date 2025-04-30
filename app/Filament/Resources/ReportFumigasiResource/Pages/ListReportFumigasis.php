<?php

namespace App\Filament\Resources\ReportFumigasiResource\Pages;

use App\Filament\Resources\ReportFumigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportFumigasis extends ListRecords
{
    protected static string $resource = ReportFumigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Laporan Fumigasi'), // Memindahkan ->label() ke baris yang sama
        ];
    }
}
