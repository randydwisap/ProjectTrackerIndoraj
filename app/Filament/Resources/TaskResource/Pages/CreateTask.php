<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Htmlable;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
public function getTitle(): Htmlable|string
{
    return 'Buat Pengolahan Arsip';
}
}
