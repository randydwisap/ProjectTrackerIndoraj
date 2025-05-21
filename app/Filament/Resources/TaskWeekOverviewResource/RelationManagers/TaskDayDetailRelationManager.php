<?php

namespace App\Filament\Resources\TaskWeekOverviewResource\RelationManagers;

use App\Models\JenisTask;
use App\Models\Task;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class TaskDayDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDayDetails';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // Jenis Tahapan
            Forms\Components\Select::make('jenis_task_id')
                ->label('Jenis Tahapan')
                ->options(JenisTask::all()->pluck('nama_task', 'id'))
                ->required()
                ->reactive(),

            // Pilih Hari
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

            // Volume Dikerjakan
           Forms\Components\TextInput::make('output')
                ->label('Volume Dikerjakan')
                ->numeric()
                ->inputMode('decimal')
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

            // Arsip
            Forms\Components\TextInput::make('hasil')
                ->label('Hasil Arsip')
                ->numeric()
                ->inputMode('decimal')
                ->reactive()
                ->readonly(fn ($get) => $get('jenis_task_id') != 1) // Nonaktifkan field jika jenis task bukan 1
                ->afterStateUpdated(fn ($set, $get) =>
                    $set('hasil_inarsip', (float) ($get('output') ?: 0) - (float) ($get('hasil') ?: 0))
                ),

            // Hasil Inarsip
            Forms\Components\TextInput::make('hasil_inarsip')
                ->label('Hasil Inarsip')
                ->numeric()
                ->readOnly(),

            // Status
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

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Laporan Harian')
                    ->mutateFormDataUsing(function (array $data) {
                        $taskDetail = $this->ownerRecord;

                        $data['task_week_overview_id'] = $taskDetail->id;
                        $data['task_id'] = $taskDetail->task_id;

                        // fallback ke input user jika tidak ada di taskDetail
                        $data['jenis_task_id'] = $taskDetail->jenis_task_id ?? $data['jenis_task_id'] ?? null;

                        if (!$data['jenis_task_id']) {
                            throw new \Exception("Jenis Task ID tidak ditemukan untuk Task Detail ID: {$taskDetail->id}");
                        }

                        return $data;
                    }),
            ]);
    }
}
