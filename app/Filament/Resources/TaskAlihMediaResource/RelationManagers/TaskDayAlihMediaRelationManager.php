<?php

namespace App\Filament\Resources\TaskAlihMediaResource\RelationManagers;

use App\Models\TaskAlihMedia;
use App\Models\JenisTaskAlihMedia;
use App\Models\TaskWeekAlihMedia;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TaskDayAlihMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDayAlihMedia';
    protected static ?string $title = 'Rekap Harian';
    protected static ?string $navigationLabel = 'Rekap Harian';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('task_week_alih_media_id')
                ->label('Pilih Week')
                ->options(function () {
                    return TaskWeekAlihMedia::where('task_alih_media_id', $this->ownerRecord->id)
                        ->pluck('nama_week', 'id');
                })
                ->required()
                ->reactive(),

            Forms\Components\Select::make('jenis_task_alih_media_id')
                ->label('Jenis Tahapan')
                ->options(JenisTaskAlihMedia::all()->pluck('nama_task', 'id'))
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
                ->inputMode('decimal')
                ->required()
                ->reactive(),

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
                Tables\Columns\TextColumn::make('taskAlihMedia.pekerjaan')
                    ->label('Nama Task')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('taskWeekAlihMedia.nama_week')
                    ->label('Week')
                    ->sortable()
                    ->searchable(),

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
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
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
                        $data['task_alih_media_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
            ]);
    }
}
