<?php

namespace App\Filament\Resources\TaskAlihMediaResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfig;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TaskWeekAlihMedia;

class TaskWeekAlihMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'taskWeekAlihMedia';
    protected static ?string $title = 'Rekap Mingguan'; // Judul header
    protected static ?string $navigationLabel = 'Rekap Mingguan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taskAlihMedia.pekerjaan') // Display task.pekerjaan instead of task_id
                ->label('Nama Task')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('nama_week') // Display nama_task from jenis_task
                ->label('Week')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_volume')
                    ->label('Target')
                    ->sortable()
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                    ->searchable(),
                Tables\Columns\TextColumn::make('volume_dikerjakan') // Display nama_task from jenis_task
                ->label('Dikerjakan')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step1') // Display nama_task from jenis_task
                ->label('Scanning')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step2') // Display nama_task from jenis_task
                ->label('Quality Control')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step3') // Display nama_task from jenis_task
                ->label('Input Data')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step4') // Display nama_task from jenis_task
                ->label('Upload Data Hyperlink')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'On Track' => 'success', // Hijau
                        'Behind Schedule' => 'warning', // Kuning
                        'Far Behind Schedule' => 'danger', // Merah
                        'Completed' => 'success', // hijau
                        'Not Started' => 'gray',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('resiko_keterlambatan')
                ->label('Keterlambatan')
                ->badge()
                ->sortable()
                ->color(fn ($state) => match ($state) {
                    'Low' => 'success',
                    'Medium' => 'warning',
                    'High' => 'danger',
                    'Completed' => 'gray',
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
