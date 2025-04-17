<?php

namespace App\Filament\Resources\JenisTahapFumigasiResource\Pages;

use App\Filament\Resources\JenisTahapFumigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisTahapFumigasi extends EditRecord
{
    protected static string $resource = JenisTahapFumigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
