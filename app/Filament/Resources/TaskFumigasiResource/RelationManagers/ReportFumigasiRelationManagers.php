<?php

namespace App\Filament\Resources\TaskFumigasiResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // Pastikan ini ada!
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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
                    Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan'),
                    Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar')
                    ->directory('report-fumigasi/gambar')
                    ->preserveFilenames()
                    ->multiple()
                    ->image()                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, callable $get): string {
                        // Jika sedang edit, gunakan ID dari model; jika baru, buat UUID unik
                        $ReportfumigasiID = $get('id') ?? Str::uuid()->toString();
                
                        // Gunakan session untuk menyimpan counter per sesi unggahan
                        $counter = session()->increment("upload_counter_{$ReportfumigasiID}", 1);
                
                        return "reportfumigasi_{$ReportfumigasiID}_gambar_{$counter}." . $file->getClientOriginalExtension();
                    }),
                    Forms\Components\FileUpload::make('lampiran')
                    ->label('Lampiran')
                    ->directory('report-fumigasi/lampiran')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $extension = $file->getClientOriginalExtension();
                        return "LampiranReportFumigasi_{$timestamp}." . $extension;
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
