<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\Task;
use App\Models\JenisTask;
use App\Models\TaskWeekOverview;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TaskDayDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDayDetails';
    protected static ?string $title = 'Rekap Harian';
    protected static ?string $navigationLabel = 'Rekap Harian';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('task_week_overview_id')
                ->label('Pilih Week')
                ->options(function () {
                    return TaskWeekOverview::where('task_id', $this->ownerRecord->id)
                        ->pluck('nama_week', 'id');
                })
                ->required()
                ->reactive(),

            Forms\Components\Select::make('jenis_task_id')
                ->label('Jenis Tahapan')
                ->options(JenisTask::all()->pluck('nama_task', 'id'))
                ->required()
                ->reactive(),

            Forms\Components\Select::make('tanggal')
                ->label('Pilih Hari')
                ->options([
                    'Day 1' => 'Day 1',
                    'Day 2' => 'Day 2',
                    'Day 3' => 'Day 3',
                    'Day 4' => 'Day 4',
                    'Day 5' => 'Day 5',
                    'Day 6' => 'Day 6',
                ])
                ->required(),

            Forms\Components\TextInput::make('output')
                ->label('Volume Dikerjakan')
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($set, $get) {
                    $output = (float) ($get('output') ?: 0);
                    $jenisTaskId = $get('jenis_task_id');
                    
                    // Jika jenis task bukan 1, maka hasil = output
                    if ($jenisTaskId != 1) {
                        $set('hasil', $output);
                    }
                    
                    // Hitung hasil_inarsip = output - hasil
                    $hasil = (float) ($get('hasil') ?: 0);
                    $set('hasil_inarsip', $output - $hasil);
            
                    // Update status berdasarkan target_perday
                    $taskId = $get('task_id');
                    $task = \App\Models\Task::find($taskId);
                    $targetPerDay = (float) ($task?->target_perday ?? 0);
            
                    if ($targetPerDay > 0) {
                        $percent = ($output / $targetPerDay) * 100;
            
                        if ($percent >= 100) {
                            $status = 'On Track';
                        } elseif ($percent > 50) {
                            $status = 'Behind Schedule';
                        } else {
                            $status = 'Far Behind Schedule';
                        }
            
                        $set('status', $status);
                    } else {
                        $set('status', 'On Track');
                    }
                }),        

            Forms\Components\TextInput::make('hasil')
                ->label('Hasil Arsip')
                ->numeric()
                ->reactive()
                ->readonly(fn ($get) => $get('jenis_task_id') != 1) // Nonaktifkan field jika jenis task bukan 1
                ->afterStateUpdated(fn ($set, $get) =>
                    $set('hasil_inarsip', (float) ($get('output') ?: 0) - (float) ($get('hasil') ?: 0))
                ),

            Forms\Components\TextInput::make('hasil_inarsip')
                ->label('Hasil Inarsip')
                ->numeric()
                ->readOnly(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'On Track' => 'On Track',
                    'Behind Schedule' => 'Behind Schedule',
                    'Far Behind Schedule' => 'Far Behind Schedule',
                    'Complete' => 'Complete',
                ])
                ->disabled()
                ->default('On Track'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task.pekerjaan')
                    ->label('Nama Task')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('taskWeekOverview.nama_week')
                    ->label('Week')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jenisTask.nama_task')
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
                        'On Track' => 'success',
                        'Behind Schedule' => 'warning',
                        'Far Behind Schedule' => 'danger',
                        'Complete' => 'gray',
                        default => 'secondary',
                    }),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Laporan Harian')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['task_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
            ]);
    }
}
