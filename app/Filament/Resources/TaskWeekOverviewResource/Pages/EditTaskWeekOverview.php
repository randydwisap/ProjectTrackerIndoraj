<?php

namespace App\Filament\Resources\TaskWeekOverviewResource\Pages;

use App\Filament\Resources\TaskWeekOverviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskWeekOverview extends EditRecord
{
    protected static string $resource = TaskWeekOverviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
