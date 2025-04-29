<?php

namespace App\Filament\Resources\TaskWeekAlihMediaResource\RelationManagers;

use App\Models\JenisTask;
use App\Models\Task;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class TaskDayAlihMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDayAlihMedia';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // Jenis Tahapan
            Forms\Components\Select::make('jenis_task_alih_media_id')
                ->label('Jenis Tahapan')
                ->options(JenisTaskAlihMedia::all()->pluck('nama_task', 'id'))
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
                ->required()
                ->reactive(),                
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
                Tables\Columns\TextColumn::make('jenisTaskAlihMedia.nama_task')
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
                        $taskAlihMedia = $this->ownerRecord;

                        $data['task_week_alih_media_id'] = $taskAlihMedia->id;
                        $data['task_alih_media_id'] = $taskAlihMedia->task_alih_media_id;

                        // fallback ke input user jika tidak ada di taskDetail
                        $data['jenis_task_alih_media_id'] = $taskAlihMedia->jenis_task_alih_media_id ?? $data['jenis_task_alih_media_id'] ?? null;

                        if (!$data['jenis_task_alih_media_id']) {
                            throw new \Exception("Jenis Task ID tidak ditemukan untuk Task Detail ID: {$taskAlihMedia->id}");
                        }

                        return $data;
                    }),
            ]);
    }
}
