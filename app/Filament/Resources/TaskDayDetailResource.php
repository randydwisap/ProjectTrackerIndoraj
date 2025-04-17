<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskDayDetailResource\Pages;
use App\Filament\Resources\TaskDayDetailResource\RelationManagers;
use App\Models\TaskDayDetail;
use App\Models\TaskDetail; // Ensure TaskDetail is imported
use Filament\Forms;
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
                    $set('task_detail_id', null);
                    $set('nama_week', null);
                    $set('jenis_task_id', null);
                }),

            // ✅ Select Nama Week
            Forms\Components\Select::make('nama_week')
            ->label('Nama Week')
            ->reactive()
            ->afterStateUpdated(fn ($set, $get) => static::resolveTaskDetailId($set, $get))
            ->options(function (callable $get) {
                return \App\Models\TaskDetail::where('task_id', $get('task_id'))
                    ->pluck('nama_week', 'nama_week')
                    ->toArray();
            })
            ->afterStateHydrated(function ($set, $record) {
                $set('nama_week', $record?->taskDetail?->nama_week);
            }),        
        
            // ✅ Select Jenis Task
            Forms\Components\Select::make('jenis_task_id')
            ->label('Jenis Tahapan')
            ->reactive()
            ->afterStateUpdated(fn ($set, $get) => static::resolveTaskDetailId($set, $get))
            ->options(function (callable $get) {
                return \App\Models\TaskDetail::where('task_id', $get('task_id'))
                    ->with('jenisTask')
                    ->get()
                    ->pluck('jenisTask.nama_task', 'jenis_task_id')
                    ->unique();
            }),

            // ✅ Hidden task_detail_id (auto-filled)
            Forms\Components\Hidden::make('task_detail_id')
            ->required()
            ->dehydrated(true) // <-- PENTING
            ->reactive()
            ->afterStateUpdated(fn ($set, $get) => static::resolveTaskDetailId($set, $get)) // Tambahkan ini
            ->afterStateHydrated(fn ($set, $get) => static::resolveTaskDetailId($set, $get)),
        
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

            Forms\Components\TextInput::make('output')->numeric()->required(),
            Forms\Components\TextInput::make('hasil')->numeric(),

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
        $taskDetail = \App\Models\TaskDetail::where('task_id', $taskId)
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

                Tables\Columns\TextColumn::make('taskDetail.nama_week') // Display nama_task from jenis_task
                    ->label('Week')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('taskDetail.jenisTask.nama_task') // Display nama_task from jenis_task
                    ->label('Nama Tahapan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Hari')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('output')
                    ->label('Volume')
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
                //
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
