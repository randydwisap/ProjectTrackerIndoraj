<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskDayAlihMediaResource\Pages;
use App\Filament\Resources\TaskDayAlihMediaResource\RelationManagers;
use App\Models\TaskDayAlihMedia;
use App\Models\TaskWeekAlihMedia;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Table;

class TaskDayAlihMediaResource extends Resource
{
    protected static ?string $model = TaskDayAlihMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Alih Media';

    protected static ?string $navigationLabel = 'Laporan Harian';

    protected static ?int $navigationSort = 2; // Menentukan urutan menu

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
    
        // Kalau bukan super_admin atau manajer, filter berdasarkan task yang dimiliki oleh user
        if (!auth()->user()?->hasAnyRole(['super_admin', 'Manajer Keuangan', 'Manajer Operasional'])) {
            $query->whereHas('taskAlihMedia', function ($q) {
                $q->where('project_manager', auth()->id());
            });
        }
    
        return $query;
    }
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('task_alih_media_id')
                ->relationship('taskAlihMedia', 'pekerjaan')
                ->required()
                ->reactive()
                ->afterStateUpdated(function (callable $set) {
                    $set('task_week_alih_media_id', null);
                    $set('nama_week', null);
                    $set('jenis_task_alih_media_id', null);
                }),
            Forms\Components\Select::make('task_week_alih_media_id')
                ->label('Nama Week')
                ->relationship('taskWeekAlihMedia', 'nama_week', fn ($query, $get) => 
                    $query->where('task_alih_media_id', $get('task_alih_media_id'))
                )
                ->required(),             
        
            // ✅ Select Jenis Task
            Forms\Components\Select::make('jenis_task_alih_media_id')
            ->label('Jenis Tahapan')
            ->reactive()            
            ->options(function () {
                return \App\Models\JenisTaskAlihMedia::all()
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
                ->label('Volume Dikerjakan (Halaman)'),            
            
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
            Tables\Columns\TextColumn::make('taskAlihMedia.pekerjaan') // Display task.pekerjaan instead of task_id
                ->label('Nama Task')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('taskWeekAlihMedia.nama_week') // Display nama_task from jenis_task
                ->label('Week')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('jenisTaskAlihMedia.nama_task') // ✅
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
            'index' => Pages\ListTaskDayAlihMedia::route('/'),
            'create' => Pages\CreateTaskDayAlihMedia::route('/create'),
            'edit' => Pages\EditTaskDayAlihMedia::route('/{record}/edit'),
        ];
    }
}
