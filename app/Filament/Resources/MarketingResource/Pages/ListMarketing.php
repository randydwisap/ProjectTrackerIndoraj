<?php

namespace App\Filament\Resources\MarketingResource\Pages;

use App\Filament\Resources\MarketingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketing extends ListRecords
{
    protected static string $resource = MarketingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Proyek'),
            Actions\Action::make('approve')
                ->label('Approve')
                ->action(function ($record) {
                    if ($record) {
                        $record->update(['status' => 'Completed']);
                    }
                })
                ->visible(fn ($record) => $record && $record->status === 'In Progress'),
            Actions\Action::make('reject')
                ->label('Reject')
                ->action(function ($record) {
                    if ($record) {
                        $record->update(['status' => 'Pending']);
                    }
                })
                ->visible(fn ($record) => $record && $record->status === 'In Progress'),
        ];
    }
}
