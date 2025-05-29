<?php

namespace App\Filament\Resources\MarketingResource\Pages;

use App\Filament\Resources\MarketingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListMarketing extends ListRecords
{
    protected static string $resource = MarketingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Proyek'),
                
Action::make('settings')
    ->label('Settings')
    ->icon('heroicon-o-cog-6-tooth')
    ->visible(fn () => auth()->user()->hasRole('Manajer Keuangan')) // Tambahkan ini
    ->form([
        TextInput::make('ppn')
            ->label('PPN (%)')
            ->numeric()
            ->required()
            ->default(fn () => Setting::first()->ppn ?? 11.00)
            ->minValue(0)
            ->maxValue(100)
            ->suffix('%'),
        TextInput::make('pph')
            ->label('PPH (%)')
            ->numeric()
            ->required()
            ->default(fn () => Setting::first()->pph ?? 2.00)
            ->minValue(0)
            ->maxValue(100)
            ->suffix('%'),
        TextInput::make('pph_barang')
            ->label('PPH Barang (%)')
            ->numeric()
            ->required()
            ->default(fn () => number_format(Setting::first()->pph_barang ?? 1.50, 2, '.', ''))
            ->minValue(0)
            ->maxValue(100)
            ->suffix('%')
    ])
    ->action(function (array $data) {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'ppn' => $data['ppn'],
                'pph' => $data['pph'],
                'pph_barang' => $data['pph_barang'],
            ]
        );
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }),
                
            Actions\Action::make('approve')
                ->label('Approve')
                ->action(function ($record) {
                    if ($record) {
                        $record->update(['status' => 'Persiapan Operasional']);
                    }
                })
                ->visible(fn ($record) => $record && $record->status === 'Pengajuan'),
                
            Actions\Action::make('reject')
                ->label('Reject')
                ->action(function ($record) {
                    if ($record) {
                        $record->update(['status' => 'Ditolak']);
                    }
                })
                ->visible(fn ($record) => $record && $record->status === 'Pengajuan'),
        ];
    }
}