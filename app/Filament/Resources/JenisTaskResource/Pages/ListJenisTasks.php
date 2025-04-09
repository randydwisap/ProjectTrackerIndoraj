<?php

namespace App\Filament\Resources\JenisTaskResource\Pages;

use App\Filament\Resources\JenisTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisTasks extends ListRecords
{
    protected static string $resource = JenisTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Tahapan'),
        ];
    }
}
