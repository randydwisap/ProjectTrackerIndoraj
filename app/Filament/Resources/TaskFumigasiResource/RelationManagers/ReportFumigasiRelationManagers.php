<?php

namespace App\Filament\Resources\TaskFumigasiResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Table;

class ReportFumigasiRelationManagers extends RelationManager
{
    protected static string $relationship = 'reportFumigasi';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenistahapfumigasi_id')
                    ->label('Tahap fumigasi')
                    ->required()
                    ->reactive()
                    ->disabled(fn ($get) => !$this->getOwnerRecord()?->id) // disable kalau parent belum terload
                    ->options(function () {
                        $taskFumigasiId = $this->getOwnerRecord()?->id;

                        if (!$taskFumigasiId) {
                            return [];
                        }

                        $usedTahapIds = \App\Models\Reportfumigasi::where('task_fumigasi_id', $taskFumigasiId)
                            ->pluck('jenistahapfumigasi_id')
                            ->toArray();

                        return \App\Models\Jenistahapfumigasi::whereNotIn('id', $usedTahapIds)
                            ->orderBy('id')
                            ->pluck('nama_task', 'id');
                    }),

                Forms\Components\DatePicker::make('tanggal')
                    ->label('Pilih Hari')
                    ->default(now())
                    ->required(),

                Forms\Components\TextInput::make('gambar'),

                Forms\Components\TextInput::make('lampiran'),

                Forms\Components\TextArea::make('keterangan')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->sortable(),
                Tables\Columns\TextColumn::make('jenistahapfumigasi.nama_task')->sortable()->label('Tahap fumigasi'),
                Tables\Columns\TextColumn::make('keterangan')->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
