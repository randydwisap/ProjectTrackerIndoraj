<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskWeekOverviewResource\Pages;
use App\Filament\Resources\TaskWeekOverviewResource\RelationManagers;
use App\Models\TaskWeekOverview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\TaskWeekOverviewResource\RelationManagers\TaskDayDetailRelationManager;

class TaskWeekOverviewResource extends Resource
{
    protected static ?string $model = TaskWeekOverview::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Pengolahan Arsip';
    protected static ?string $navigationLabel = 'Rekap Mingguan';
    protected static ?int $navigationSort = 4; // Menentukan urutan menu
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
                        $set('task_detail_id', null); // Reset task_detail_id when task_id changes
                    }),
                Forms\Components\TextInput::make('nama_week')
                    ->required(),
                    Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'On Track' => 'On Track',
                        'Behind Schedule' => 'Behind Schedule',
                        'Far Behind Schedule' => 'Far Behind Schedule',
                        'Complete' => 'Complete',
                    ])
                    ->required(),
                Forms\Components\Select::make('resiko_keterlambatan')
                    ->label('Resiko Keterlambatan')
                    ->options([
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High',
                    ])
                    ->default('Low'),
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
                Tables\Columns\TextColumn::make('nama_week') // Display nama_task from jenis_task
                ->label('Week')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('volume_dikerjakan') // Display nama_task from jenis_task
                ->label('Dikerjakan')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('arsip') // Display nama_task from jenis_task
                ->label('Arsip')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('inarsip') // Display nama_task from jenis_task
                ->label('Inarsip')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step1') // Display nama_task from jenis_task
                ->label('Pemilahan dan Identifikasi')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step2') // Display nama_task from jenis_task
                ->label('Manuver dan Pemberkasan')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step3') // Display nama_task from jenis_task
                ->label('Input Data')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step4') // Display nama_task from jenis_task
                ->label('Pelabelan dan Penataan')
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                SelectFilter::make('task_id')
                    ->label('Filter Pekerjaan')
                    ->searchable()
                    ->options(fn () => \App\Models\Task::pluck('pekerjaan', 'id')->toArray()),
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->searchable()
                    ->options(fn () => TaskWeekOverview::query()->distinct()->pluck('status', 'status')->toArray()),
                SelectFilter::make('resiko_keterlambatan')
                    ->label('Filter Resiko')
                    ->searchable()
                    ->options(fn () => TaskWeekOverview::query()->distinct()->pluck('resiko_keterlambatan', 'resiko_keterlambatan')->toArray()),
                SelectFilter::make('nama_week')
                    ->label('Filter Week')
                    ->searchable()
                    ->options(fn () => TaskWeekOverview::query()->distinct()->pluck('nama_week', 'nama_week')->toArray()),
                
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TaskDayDetailRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskWeekOverviews::route('/'),
            //'create' => Pages\CreateTaskWeekOverview::route('/create'),
            'edit' => Pages\EditTaskWeekOverview::route('/{record}/edit'),
        ];
    }
}
