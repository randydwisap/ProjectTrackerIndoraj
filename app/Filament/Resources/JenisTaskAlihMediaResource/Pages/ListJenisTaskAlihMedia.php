<?php

namespace App\Filament\Resources\JenisTaskAlihMediaResource\Pages;

use App\Filament\Resources\JenisTaskAlihMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisTaskAlihMedia extends ListRecords
{
    protected static string $resource = JenisTaskAlihMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Master Jenis Tahapan'), // Memindahkan ->label() ke baris yang sama
        ];
    }
}
