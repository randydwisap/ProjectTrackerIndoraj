<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskWeekAlihMediaResource\Pages;
use App\Filament\Resources\TaskWeekAlihMediaResource\RelationManagers;
use App\Models\TaskWeekAlihMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\TaskWeekAlihMediaResource\RelationManagers\TaskDayAlihMediaRelationManager;

class TaskWeekAlihMediaResource extends Resource
{
    protected static ?string $model = TaskWeekAlihMedia::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Alih Media';
    protected static ?string $navigationLabel = 'Rekap Mingguan';
    protected static ?int $navigationSort = 3; // Menentukan urutan menu


    public static function getRelations(): array
    {
        return [
            TaskDayAlihMediaRelationManager::class,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('task_alih_media_id')
                    ->relationship('taskAlihMedia', 'pekerjaan')
                    ->required()
                    ->reactive(),

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
                Tables\Columns\TextColumn::make('taskAlihMedia.pekerjaan') // Display taskAlihMedia.pekerjaan instead of task_id
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
                Tables\Columns\TextColumn::make('total_step1') // Display nama_task from jenis_task
                ->label('Scanning')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step2') // Display nama_task from jenis_task
                ->label('Quality Control')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step3') // Display nama_task from jenis_task
                ->label('Input Data')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step4') // Display nama_task from jenis_task
                ->label('Upload Data Hyperlink')
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
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskWeekAlihMedia::route('/'),
            'create' => Pages\CreateTaskWeekAlihMedia::route('/create'),
            'edit' => Pages\EditTaskWeekAlihMedia::route('/{record}/edit'),
        ];
    }
}
