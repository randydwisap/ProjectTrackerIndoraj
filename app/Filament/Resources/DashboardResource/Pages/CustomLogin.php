<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use Filament\Resources\Pages\Page;

class CustomLogin extends Page
{
    protected static string $resource = DashboardResource::class;

    protected static string $view = 'filament.pages.custom-login';
}
