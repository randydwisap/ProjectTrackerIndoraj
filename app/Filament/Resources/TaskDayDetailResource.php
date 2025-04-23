<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskDayDetailResource\Pages;
use App\Filament\Resources\TaskDayDetailResource\RelationManagers;
use App\Models\TaskDayDetail;
use App\Models\TaskWeekOverview;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskDayDetailResource extends Resource
{
    protected static ?string $model = TaskDayDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Pengolahan Arsip';

    protected static ?string $navigationLabel = 'Laporan Harian';

    protected static ?int $navigationSort = 3; // Menentukan urutan menu

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('task_id')
                ->relationship('task', 'pekerjaan')
                ->required()
                ->reactive()
                ->afterStateUpdated(function (callable $set) {
                    $set('task_week_overview_id', null);
                    $set('nama_week', null);
                    $set('jenis_task_id', null);
                }),
            Forms\Components\Select::make('task_week_overview_id')
                ->label('Nama Week')
                ->relationship('taskWeekOverview', 'nama_week', fn ($query, $get) => 
                    $query->where('task_id', $get('task_id'))
                )
                ->required(),             
        
            // ✅ Select Jenis Task
            Forms\Components\Select::make('jenis_task_id')
            ->label('Jenis Tahapan')
            ->reactive()            
            ->options(function () {
                return \App\Models\JenisTask::all()
                    ->pluck('nama_task', 'id') // id sebagai value, nama_task sebagai label
                    ->toArray();
            }),        

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
                ->required()
                ->label('Volume Dikerjakan')
                ->reactive()
                ->afterStateUpdated(function ($set, $get) {
                    // 1. Hitung hasil_inarsip = output - hasil
                    $output = (float) ($get('output') ?: 0);
                    $hasil = (float) ($get('hasil') ?: 0);
                    $set('hasil_inarsip', $output - $hasil);
            
                    // 2. Update status berdasarkan target_perday
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
                        $set('status', 'On Track'); // default kalau gak ada target
                    }
                }),            
            
            Forms\Components\TextInput::make('hasil')
                ->numeric()
                ->label('Hasil Arsip')
                ->reactive()
                ->afterStateUpdated(fn ($set, $get) =>
                    $set('hasil_inarsip', (float) ($get('output') ?: 0) - (float) ($get('hasil') ?: 0))
                ),
            
            Forms\Components\TextInput::make('hasil_inarsip')
                ->numeric()
                ->label('Hasil Inarsip')
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
protected static function resolveTaskDetailId(callable $set, callable $get): void
{
    $taskId = $get('task_id');
    $namaWeek = $get('nama_week');
    $jenisTaskId = $get('jenis_task_id');

    if ($taskId && $namaWeek && $jenisTaskId) {
        $taskDetail = \App\Models\TaskWeekOverview::where('task_id', $taskId)
            ->where('nama_week', $namaWeek)
            ->where('jenis_task_id', $jenisTaskId)
            ->first();

        $set('task_detail_id', $taskDetail?->id);
    }
}


    public static function table(Table $table): Table
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
            ->filters([
                SelectFilter::make('task_id')
                ->label('Filter Pekerjaan')
                ->searchable()
                ->options(fn () => \App\Models\Task::pluck('pekerjaan', 'id')->toArray()),
                SelectFilter::make('task_week_overview_id')
                ->label('Filter Week')
                ->searchable()
                ->options(fn () => \App\Models\TaskWeekOverview::pluck('nama_week', 'id')->toArray()),
                SelectFilter::make('jenis_task_id')
                ->label('Filter Tahap')
                ->searchable()
                ->options(fn () => \App\Models\JenisTask::pluck('nama_task', 'id')->toArray()),
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->searchable()
                    ->options(fn () => TaskDayDetail::query()->distinct()->pluck('status', 'status')->toArray()),
                SelectFilter::make('tanggal')
                    ->label('Filter Tanggal')
                    ->searchable()
                    ->options(fn () => TaskDayDetail::query()->distinct()->pluck('tanggal', 'tanggal')->toArray()),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskDayDetails::route('/'),
            'create' => Pages\CreateTaskDayDetail::route('/create'),
            'edit' => Pages\EditTaskDayDetail::route('/{record}/edit'),
        ];
    }
}
