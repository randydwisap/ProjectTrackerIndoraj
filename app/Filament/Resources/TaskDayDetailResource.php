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
use Illuminate\Database\Eloquent\Builder;

class TaskDayDetailResource extends Resource
{
    protected static ?string $model = TaskDayDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Pengolahan Arsip';

    protected static ?string $navigationLabel = 'Laporan Harian';

    protected static ?int $navigationSort = 3; // Menentukan urutan menu
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
    
        // Kalau bukan super_admin atau manajer, filter berdasarkan task yang dimiliki oleh user
        if (!auth()->user()?->hasAnyRole(['super_admin', 'Manajer Keuangan', 'Manajer Operasional'])) {
            $query->whereHas('task', function ($q) {
                $q->where('project_manager', auth()->id());
            });
        }
    
        return $query;
    }
    
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
                ->inputMode('decimal')
                ->required()
                ->label('Volume Dikerjakan')
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
                ->numeric()
                ->inputMode('decimal')
                ->label('Hasil Arsip')
                ->reactive()
                ->readonly(fn ($get) => $get('jenis_task_id') != 1) // Nonaktifkan field jika jenis task bukan 1
                ->afterStateUpdated(fn ($set, $get) =>
                    $set('hasil_inarsip', (float) ($get('output') ?: 0) - (float) ($get('hasil') ?: 0))
                ),
            
            Forms\Components\TextInput::make('hasil_inarsip')
                ->numeric()
                ->inputMode('decimal')
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
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                    ->sortable(),
                Tables\Columns\TextColumn::make('hasil')
                    ->label('Arsip')
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                    ->sortable(),
                Tables\Columns\TextColumn::make('hasil_inarsip')
                    ->label('Inarsip')
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
                ->options(fn () => \App\Models\TaskWeekOverview::pluck('nama_week', 'nama_week')->toArray()),
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
