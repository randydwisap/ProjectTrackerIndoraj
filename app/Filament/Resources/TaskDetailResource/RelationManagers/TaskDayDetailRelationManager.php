<?php

namespace App\Filament\Resources\TaskDetailResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\TaskDetail;

class TaskDayDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDayDetails';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
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
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('hasil')
                    ->numeric(),

                Forms\Components\Hidden::make('jenis_task_id'), // Auto-set dari taskDetail

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
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Hari')
                    ->sortable(),

                Tables\Columns\TextColumn::make('task.target_perday')
                    ->label('Target')
                    ->sortable(),

                Tables\Columns\TextColumn::make('output')
                    ->label('Dikerjakan')
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
                        $taskDetail = $this->ownerRecord; // Ambil TaskDetail terkait
                        $data['task_detail_id'] = $taskDetail->id;
                        $data['task_id'] = $taskDetail->task_id;
                        $data['jenis_task_id'] = $taskDetail->jenis_task_id ?? null; // Ambil jenis_task_id langsung dari taskDetail

                                        // Cek apakah task_id dan jenis_task_id tidak null
                                        if (!$data['jenis_task_id']) {
                                            throw new \Exception("Jenis Task ID tidak ditemukan untuk Task Detail ID: {$taskDetail->id}");
                                        }
                        return $data;
                    }),
            ]);
    }
}
