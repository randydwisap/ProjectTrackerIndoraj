<?php

namespace App\Filament\Resources\TaskDetailResource\Pages;

use App\Filament\Resources\TaskDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskDetail extends CreateRecord
{
    protected static string $resource = TaskDetailResource::class;
}
