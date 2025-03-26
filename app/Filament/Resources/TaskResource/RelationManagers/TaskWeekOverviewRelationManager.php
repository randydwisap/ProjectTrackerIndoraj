<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfig;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TaskWeekOverview;

class TaskWeekOverviewRelationManager extends RelationManager
{
    protected static string $relationship = 'taskWeekOverviews';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_week')
                    ->label('Minggu Ke')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'On Track' => 'success',
                        'Behind Schedule' => 'warning',
                        'Far Behind Schedule' => 'danger',
                        'Completed' => 'success',
                        'Not Started' => 'gray',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('resiko_keterlambatan')
                    ->label('Keterlambatan')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'Low' => 'success',
                        'Moderate' => 'warning',
                        'High' => 'danger',
                        'Completed' => 'success',
                        default => 'secondary',
                    }),
            ])
            ->filters([])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
