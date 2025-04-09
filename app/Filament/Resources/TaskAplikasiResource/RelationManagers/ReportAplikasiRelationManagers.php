<?php

namespace App\Filament\Resources\TaskAplikasiResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Table;

class ReportAplikasiRelationManagers extends RelationManager
{
    protected static string $relationship = 'reportAplikasi';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenistahapaplikasi_id')
                    ->label('Tahap Aplikasi')
                    ->required()
                    ->reactive()
                    ->disabled(fn ($get) => !$this->getOwnerRecord()?->id) // disable kalau parent belum terload
                    ->options(function () {
                        $taskAplikasiId = $this->getOwnerRecord()?->id;

                        if (!$taskAplikasiId) {
                            return [];
                        }

                        $usedTahapIds = \App\Models\ReportAplikasi::where('task_aplikasi_id', $taskAplikasiId)
                            ->pluck('jenistahapaplikasi_id')
                            ->toArray();

                        return \App\Models\Jenistahapaplikasi::whereNotIn('id', $usedTahapIds)
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
                Tables\Columns\TextColumn::make('jenistahapaplikasi.nama_task')->sortable()->label('Tahap Aplikasi'),
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
