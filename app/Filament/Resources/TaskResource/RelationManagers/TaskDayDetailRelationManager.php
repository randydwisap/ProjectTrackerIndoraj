<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfig;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TaskDayDetail;

class TaskDayDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDayDetails';
    protected static ?string $title = 'Rekap Harian'; // Judul header
    protected static ?string $navigationLabel = 'Rekap Harian';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task.pekerjaan') // Display task.pekerjaan instead of task_id
                ->label('Nama Task')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('taskWeekOverview.nama_week') // Display nama_task from jenis_task
                ->label('Week')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('jenisTask.nama_task') // ✅
                ->label('Nama Tahapan')
                ->sortable()
                ->searchable(),
            
            Tables\Columns\TextColumn::make('tanggal')
                ->label('Hari')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('output')
                ->label('Dikerjakan')
                ->sortable(),
            Tables\Columns\TextColumn::make('hasil')
                ->label('Arsip')
                ->sortable(),
            Tables\Columns\TextColumn::make('hasil_inarsip')
                ->label('Inarsip')
                ->sortable(),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->sortable()
                ->color(fn ($state) => match ($state) {
                    'On Track' => 'success', // Hijau
                    'Behind Schedule' => 'warning', // Kuning
                    'Far Behind Schedule' => 'danger', // Merah
                    'Complete' => 'gray', // Abu-abu
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
