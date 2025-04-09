<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskDetailResource\Pages;
use App\Filament\Resources\TaskDetailResource\RelationManagers;
use App\Filament\Resources\TaskDetailResource\RelationManagers\TaskDayDetailRelationManager;
use App\Models\TaskDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskDetailResource extends Resource
{
    protected static ?string $model = TaskDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Pengolahan Arsip';
    protected static ?int $navigationSort = 2; // Menentukan urutan menu
    // Updated navigation label
    protected static ?string $navigationLabel = 'Laporan Mingguan';

    public static function getRelations(): array
    {
        return [
            TaskDayDetailRelationManager::class,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('task_id')->relationship('task', 'pekerjaan')->required()->disabled(),
                Forms\Components\Select::make('jenis_task_id')->relationship('jenisTask', 'nama_task')->required()->disabled(),
                Forms\Components\TextInput::make('nama_week')->required()->disabled(),
                Forms\Components\TextInput::make('total_volume')->numeric()->required()->disabled(),
                Forms\Components\TextInput::make('volume_dikerjakan')->numeric()->default(0)->disabled(),   
                Forms\Components\TextInput::make('hasil')->numeric(),             
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
                Forms\Components\Hidden::make('sisa_volume')->default(0)->disabled(), // Updated to Hidden karena sudah set trigger untuk menghitung sisa volume didatabase
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task.pekerjaan') // Display task.pekerjaan instead of task_id
                    ->label('Nama Pekerjaan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('task.klien') // Display task.pekerjaan instead of task_id
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
                        'Completed' => 'success', // Hijau
                        'Behind Schedule' => 'danger', // Merah
                        'In Progress' => 'warning', // Kuning
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


    public static function getRelationManagers(): array
    {
        return [
            TaskDayDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskDetails::route('/'),
            'create' => Pages\CreateTaskDetail::route('/create'),
            'edit' => Pages\EditTaskDetail::route('/{record}/edit'),
        ];
    }
}
