<?php

namespace App\Filament\Resources\TaskWeekOverviewResource\Pages;

use App\Filament\Resources\TaskWeekOverviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskWeekOverviews extends ListRecords
{
    protected static string $resource = TaskWeekOverviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
