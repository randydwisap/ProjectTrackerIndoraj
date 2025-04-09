<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportAplikasiResource\Pages;
use App\Filament\Resources\ReportAplikasiResource\RelationManagers;
use App\Models\ReportAplikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportAplikasiResource extends Resource
{
    protected static ?string $model = ReportAplikasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Report';
    protected static ?string $navigationGroup = 'Proyek Aplikasi';
    protected static ?string $pluralLabel = 'Report';
    protected static ?int $navigationSort = 3; // Menentukan urutan menu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('task_aplikasi_id')
                ->relationship('taskaplikasi', 'pekerjaan')
                ->required()
                ->reactive()
                ->label('Pekerjaan Aplikasi'),
                Forms\Components\Select::make('jenistahapaplikasi_id')
                ->label('Tahap Aplikasi')
                ->required()
                ->reactive()
                ->hidden(fn ($get) => !$get('task_aplikasi_id')) // Disable kalau belum dipilih
                ->options(function ($get) {
                    $taskAplikasiId = $get('task_aplikasi_id');
            
                    // Jika belum dipilih, tampilkan semua
                    if (!$taskAplikasiId) {
                        return \App\Models\Jenistahapaplikasi::orderBy('id')
                            ->pluck('nama_task', 'id');
                    }
            
                    // Ambil ID jenistahapaplikasi yang sudah digunakan oleh taskaplikasi_id ini
                    $usedTahapIds = \App\Models\ReportAplikasi::where('task_aplikasi_id', $taskAplikasiId)
                        ->pluck('jenistahapaplikasi_id')
                        ->toArray();
            
                    // Tampilkan hanya yang belum digunakan
                    return \App\Models\Jenistahapaplikasi::whereNotIn('id', $usedTahapIds)
                        ->orderBy('id')
                        ->pluck('nama_task', 'id');
                }),
                    
            
            Forms\Components\DatePicker::make('tanggal') // Updated to map to 'tanggal'
                ->label('Pilih Hari')
                ->default(now())
                ->required(),            
            Forms\Components\TextInput::make('gambar'),               
            Forms\Components\TextInput::make('lampiran'),
            Forms\Components\TextArea::make('keterangan')->required(),          
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taskaplikasi.pekerjaan')->sortable()->label('Pekerjaan'),
                Tables\Columns\TextColumn::make('tanggal')->sortable(),
                Tables\Columns\TextColumn::make('jenistahapaplikasi.nama_task')->sortable()->label('Tahap Aplikasi'),
                Tables\Columns\TextColumn::make('keterangan')->sortable(),
                Tables\Columns\TextColumn::make('gambar')->sortable(),
                Tables\Columns\TextColumn::make('lampiran')->sortable(),
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
            'index' => Pages\ListReportAplikasis::route('/'),
            'create' => Pages\CreateReportAplikasi::route('/create'),
            'edit' => Pages\EditReportAplikasi::route('/{record}/edit'),
        ];
    }
}
