<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\TaskDetail;

class TaskDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'taskDetails';
    protected static ?string $navigationLabel = 'Laporan Mingguan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task.pekerjaan')
                    ->label('Nama Pekerjaan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('task.klien')
                    ->label('Nama Klien')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_week')
                    ->label('Nama Minggu')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_volume')
                    ->label('Total Volume')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenistask.nama_task')
                    ->label('Nama Task')
                    ->sortable(),

                Tables\Columns\TextColumn::make('volume_dikerjakan')
                    ->label('Volume Dikerjakan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hasil')
                    ->label('Hasil Pemilahan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sisa_volume')
                    ->label('Sisa Volume')
                    ->sortable()
                    ->formatStateUsing(fn (TaskDetail $record) => 
                        ($record->total_volume ?? 0) - ($record->volume_dikerjakan ?? 0) < 0 ? 0 : ($record->total_volume - $record->volume_dikerjakan)
                    ),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'Completed' => 'success', 
                        'Behind Schedule' => 'danger', 
                        'In Progress' => 'warning', 
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
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * ✅ Tambahkan Form untuk View & Edit
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('task_id')
                    ->relationship('task', 'pekerjaan')
                    ->required()
                    ->disabled(),

                Forms\Components\Select::make('jenis_task_id')
                    ->relationship('jenisTask', 'nama_task')
                    ->required()
                    ->disabled(),

                Forms\Components\TextInput::make('nama_week')
                    ->required()
                    ->disabled(),

                Forms\Components\TextInput::make('total_volume')
                    ->numeric()
                    ->required()
                    ->disabled(),

                Forms\Components\TextInput::make('volume_dikerjakan')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                Forms\Components\TextInput::make('hasil')
                    ->numeric(),

                Forms\Components\Select::make('resiko_keterlambatan')
                    ->label('Resiko Keterlambatan')
                    ->disabled()
                    ->options([
                        'Low' => 'Low',
                        'Moderate' => 'Moderate',
                        'High' => 'High',
                        'Completed' => 'Completed',
                    ])
                    ->default('Low'),

                Forms\Components\Hidden::make('sisa_volume')
                    ->default(0)
                    ->disabled(),
            ]);
    }
}
