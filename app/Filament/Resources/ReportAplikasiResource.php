<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportAplikasiResource\Pages;
use App\Filament\Resources\ReportAplikasiResource\RelationManagers;
use App\Models\ReportAplikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // Pastikan ini ada!
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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
    protected static ?int $navigationSort = 2; // Menentukan urutan menu

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
                        return \App\Models\JenisTahapAplikasi::orderBy('id')
                            ->pluck('nama_task', 'id');
                    }
            
                    // Ambil ID jenistahapaplikasi yang sudah digunakan oleh taskaplikasi_id ini
                    $usedTahapIds = \App\Models\ReportAplikasi::where('task_aplikasi_id', $taskAplikasiId)
                        ->pluck('jenistahapaplikasi_id')
                        ->toArray();
            
                    // Tampilkan hanya yang belum digunakan
                    return \App\Models\JenisTahapAplikasi::whereNotIn('id', $usedTahapIds)
                        ->orderBy('id')
                        ->pluck('nama_task', 'id');
                }),
                    
            
            Forms\Components\DatePicker::make('tanggal') // Updated to map to 'tanggal'
                ->label('Pilih Hari')
                ->default(now())
                ->required(),            
                Forms\Components\Textarea::make('keterangan')
                ->label('Keterangan'),                    
            Forms\Components\FileUpload::make('gambar')
            ->label('Gambar')
            ->directory('report-aplikasi/gambar')
            ->preserveFilenames()
            ->multiple()
            ->image()                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, callable $get): string {
                // Jika sedang edit, gunakan ID dari model; jika baru, buat UUID unik
                $ReportaplikasiID = $get('id') ?? Str::uuid()->toString();
        
                // Gunakan session untuk menyimpan counter per sesi unggahan
                $counter = session()->increment("upload_counter_{$ReportaplikasiID}", 1);
        
                return "reportaplikasi{$ReportaplikasiID}_gambar_{$counter}." . $file->getClientOriginalExtension();
            }),
            Forms\Components\FileUpload::make('lampiran')
            ->label('Lampiran')
            ->directory('report-aplikasi/lampiran')
            ->preserveFilenames()
            ->getUploadedFileNameForStorageUsing(function ($file) {
                $timestamp = now()->format('Ymd_His');
                $extension = $file->getClientOriginalExtension();
                return "LampiranReportAplikasi_{$timestamp}." . $extension;
            })
            ->acceptedFileTypes(['application/pdf', 'application/zip', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),              
            ]);
    }
    public static function getLampiranUrl($record): string
    {
        return asset('ProjectTrackerIndoraj/storage/app/public/' . $record->lampiran);
        //return asset('storage/' . $record->lampiran);
    }

    public static function getDokumentasiFotoUrls($record): array
    {
        return array_map(function ($gambar) {
            return asset('ProjectTrackerIndoraj/storage/app/public/' . $gambar);
            //return asset('storage/' . $gambar);
        }, $record->gambar);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taskaplikasi.pekerjaan')->sortable()->label('Pekerjaan'),
                Tables\Columns\TextColumn::make('tanggal')->sortable(),
                Tables\Columns\TextColumn::make('jenistahapaplikasi.nama_task')->sortable()->label('Tahap Aplikasi'),
                Tables\Columns\TextColumn::make('keterangan')->sortable(),
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
                        'imageUrls' => self::getDokumentasiFotoUrls($record), // Ambil URL gambar dari helper function
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
            'index' => Pages\ListReportAplikasis::route('/'),
            'create' => Pages\CreateReportAplikasi::route('/create'),
            'edit' => Pages\EditReportAplikasi::route('/{record}/edit'),
        ];
    }
}
