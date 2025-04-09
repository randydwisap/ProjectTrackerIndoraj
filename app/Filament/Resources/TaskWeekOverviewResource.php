<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskWeekOverviewResource\Pages;
use App\Filament\Resources\TaskWeekOverviewResource\RelationManagers;
use App\Models\TaskWeekOverview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\TaskDetail; // Ensure TaskDetail is imported
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class TaskWeekOverviewResource extends Resource
{
    protected static ?string $model = TaskWeekOverview::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Pengolahan Arsip';
    protected static ?string $navigationLabel = 'Rekap Mingguan';
    protected static ?int $navigationSort = 4; // Menentukan urutan menu

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
                    'Moderate' => 'warning',
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
            'index' => Pages\ListTaskWeekOverviews::route('/'),
            'create' => Pages\CreateTaskWeekOverview::route('/create'),
            'edit' => Pages\EditTaskWeekOverview::route('/{record}/edit'),
        ];
    }
}
