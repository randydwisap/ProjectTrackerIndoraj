<?php

namespace App\Filament\Resources\JenisTahapFumigasiResource\Pages;

use App\Filament\Resources\JenisTahapFumigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisTahapFumigasis extends ListRecords
{
    protected static string $resource = JenisTahapFumigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
