<?php

namespace App\Filament\Resources\JenisTahapAplikasiResource\Pages;

use App\Filament\Resources\JenisTahapAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisTahapAplikasi extends EditRecord
{
    protected static string $resource = JenisTahapAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
