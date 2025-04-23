<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportFumigasiResource\Pages;
use App\Filament\Resources\ReportFumigasiResource\RelationManagers;
use App\Models\ReportFumigasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportfumigasiResource extends Resource
{
    protected static ?string $model = ReportFumigasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Report Harian';
    protected static ?string $navigationGroup = 'Pengolahan Fumigasi';
    protected static ?string $pluralLabel = 'Report';
    protected static ?int $navigationSort = 3; // Menentukan urutan menu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('task_fumigasi_id')
                ->relationship('taskfumigasi', 'pekerjaan')
                ->required()
                ->reactive()
                ->label('Pekerjaan fumigasi'),

                Forms\Components\Select::make('jenistahapfumigasi_id')
                ->label('Tahap fumigasi')
                ->required()
                ->reactive()
                ->hidden(fn ($get) => !$get('task_fumigasi_id')) // Disable kalau belum dipilih
                ->options(function ($get) {
                    $taskFumigasiId = $get('task_fumigasi_id');
            
                    // Jika belum dipilih, tampilkan semua
                    if (!$taskFumigasiId) {
                        return \App\Models\Jenistahapfumigasi::orderBy('id')
                            ->pluck('nama_task', 'id');
                    }
            
                    // Ambil ID jenistahapfumigasi yang sudah digunakan oleh taskfumigasi_id ini
                    $usedTahapIds = \App\Models\ReportFumigasi::where('task_fumigasi_id', $taskFumigasiId)
                        ->pluck('jenistahapfumigasi_id')
                        ->toArray();
            
                    // Tampilkan hanya yang belum digunakan
                    return \App\Models\JenistahapFumigasi::whereNotIn('id', $usedTahapIds)
                        ->orderBy('id')
                        ->pluck('nama_task', 'id');
                }),                    
            
            Forms\Components\DatePicker::make('tanggal') // Updated to map to 'tanggal'
                ->label('Pilih Hari')
                ->default(now())
                ->required(),                         
            Forms\Components\FileUpload::make('gambar')
            ->label('Gambar')
            ->directory('report-fumigasi/gambar')
            ->preserveFilenames()
            ->image(),
            Forms\Components\FileUpload::make('lampiran')
            ->label('Lampiran')
            ->directory('report-fumigasi/lampiran')
            ->preserveFilenames()
            ->acceptedFileTypes(['application/pdf', 'application/zip', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),              
            ]);
    }
    public static function getLampiranUrl($record): string
    {
        //return asset('ProjectTrackerIndoraj/storage/app/public/' . $record->lampiran);
        return asset('storage/' . $record->lampiran);
    }

    public static function getDokumentasiFotoUrls($record): string
    {
        //return asset('ProjectTrackerIndoraj/storage/app/public/' . $record->lampiran);
        return asset('storage/' . $record->gambar);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taskfumigasi.pekerjaan')->sortable()->label('Pekerjaan'),
                Tables\Columns\TextColumn::make('tanggal')->sortable(),
                Tables\Columns\TextColumn::make('jenistahapfumigasi.nama_task')->sortable()->label('Tahap fumigasi'),
                Tables\Columns\TextColumn::make('keterangan')->sortable(),
                Tables\Columns\TextColumn::make('gambar')->sortable(),
                Tables\Columns\TextColumn::make('lampiran')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('preview_pdf')
                    ->label('')
                    ->icon('heroicon-o-document')
                    ->color('primary')
                    ->modalHeading('Preview Lampiran')
                    ->modalWidth('max-w-7xl') // Lebih fleksibel
                    ->modalContent(fn ($record) => view('components.pdf-viewer', [
                        'pdfUrl' => self::getLampiranUrl($record),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),      
                Tables\Actions\Action::make('preview_images')
                    ->label('')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->modalHeading('Preview Dokumentasi')
                    ->modalWidth('max-w-4xl') // Ukuran modal lebih besar agar gambar terlihat jelas
                    ->modalContent(fn ($record) => view('components.image-viewer', [
                        'imageUrl' => self::getDokumentasiFotoUrls($record), // Ambil URL gambar dari helper function
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
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
            'index' => Pages\ListReportFumigasis::route('/'),
            'create' => Pages\CreateReportFumigasi::route('/create'),
            'edit' => Pages\EditReportFumigasi::route('/{record}/edit'),
        ];
    }
}
