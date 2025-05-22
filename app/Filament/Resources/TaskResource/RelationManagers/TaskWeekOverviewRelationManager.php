<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfig;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TaskWeekOverview;

class TaskWeekOverviewRelationManager extends RelationManager
{
    protected static string $relationship = 'taskWeekOverviews';
    protected static ?string $title = 'Rekap Mingguan'; // Judul header
    protected static ?string $navigationLabel = 'Rekap Mingguan';
    public function table(Table $table): Table
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
                Tables\Columns\TextColumn::make('hari_kerja') // Display nama_task from jenis_task
                ->label('Hari Kerja')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_tanggal_unik')
                ->label('Hari Input')
                ->sortable()
                ->numeric()
                ->getStateUsing(fn ($record) => $record->jumlah_tanggal_unik),
                Tables\Columns\TextColumn::make('target_minggu') // Display nama_task from jenis_task
                ->label('Target')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('volume_dikerjakan') // Display nama_task from jenis_task
                ->label('Dikerjakan')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('arsip') // Display nama_task from jenis_task
                ->label('Arsip')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('inarsip') // Display nama_task from jenis_task
                ->label('Inarsip')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step1') // Display nama_task from jenis_task
                ->label('Pemilahan dan Identifikasi')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step2') // Display nama_task from jenis_task
                ->label('Manuver dan Pemberkasan')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step3') // Display nama_task from jenis_task
                ->label('Input Data')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_step4') // Display nama_task from jenis_task
                ->label('Pelabelan dan Penataan')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
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
            ->filters([])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

}
