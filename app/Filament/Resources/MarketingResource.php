<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingResource\Pages;
use App\Models\Marketing;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class MarketingResource extends Resource
{
    protected static ?string $model = Marketing::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-rupee';

    protected static ?string $navigationGroup = 'Marketing';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pekerjaan')->required()->label('Nama Pekerjaan'),
                Forms\Components\TextInput::make('jenis_pekerjaan')->required()->label('Jenis Pekerjaan'),
                Forms\Components\TextInput::make('nama_klien')->required()->label('Nama Klien'),
                Forms\Components\TextInput::make('lokasi')->required(),
                Forms\Components\TextInput::make('tahap_pengerjaan')->required(),
                Forms\Components\TextInput::make('total_volume')->label('Volume Arsip (mL)')->numeric()->required(),
                Forms\Components\Select::make('nama_pic')->relationship('pic', 'name')->required()->label('PIC Projek'),
                Forms\Components\Select::make('project_manager')->relationship('manager', 'name')->required()->label('Nama Project Manager'),
                Forms\Components\Select::make('status')->options([
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                    'On Hold' => 'On Hold',
                ])->required()->label('Status Pekerjaan')->default('Pending')
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state === 'Completed') {
                        // Logic for when status is set to Completed
                    }
                    if ($state === 'Pending') {
                        // Logic for when status is set to Pending
                    }
                }),
                Forms\Components\TextInput::make('durasi_proyek')
                ->label('Durasi Proyek (Minggu)')
                ->numeric()
                ->required()
                ->disabled(), // Non-editable karena dihitung otomatis  
                Forms\Components\TextInput::make('jumlah_sdm')->required()->label('Jumlah SDM')->numeric(),
                Forms\Components\TextInput::make('nilai_proyek')->required()->label('Nilai Proyek')->numeric()->prefix('Rp '),
                Forms\Components\TextInput::make('link_rab')->nullable()->label('Link RAB'), Forms\Components\DatePicker::make('tgl_mulai')
                ->label('Tanggal Mulai')
                ->required()
                ->default(now())
                ->reactive() // Agar memicu perhitungan otomatis
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                    self::hitungDurasiProyek($set, $get)
                ),

                Forms\Components\DatePicker::make('tgl_selesai')
                ->label('Tanggal Selesai')
                ->required()
                ->default(now())
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                    self::hitungDurasiProyek($set, $get)
                ),
                Forms\Components\TextInput::make('nilai_akhir_proyek')->required()->label('Nilai Akhir Proyek')->numeric()->prefix('Rp '),
                Forms\Components\TextInput::make('terms_of_payment')->label('Terms of Payment')->numeric()->prefix('Day ')->required()->default(60),
                Forms\Components\Select::make('status_pembayaran')->options([
                    'Belum Lunas' => 'Belum Lunas',
                    'Lunas' => 'Lunas',
                ])->required()->label('Status Pembayaran')->default('Belum Lunas'),
                Forms\Components\FileUpload::make('dokumentasi_foto')->multiple()->required(),
                Forms\Components\FileUpload::make('lampiran')->required()->acceptedFileTypes(['application/pdf']),
                Forms\Components\TextInput::make('note')->required(),
            ]);
    }

    public static function getLampiranUrl($record): string
    {
        return asset('storage/' . $record->lampiran);
    }

    public static function getDokumentasiFotoUrls($record): array
    {
        return array_map(function ($foto) {
            return asset('storage/' . $foto);
        }, $record->dokumentasi_foto);
    }

    public static function hitungDurasiProyek(callable $set, callable $get)
{
    $tglMulai = $get('tgl_mulai');
    $tglSelesai = $get('tgl_selesai');

    if ($tglMulai && $tglSelesai) {
        $start = \Carbon\Carbon::parse($tglMulai);
        $end = \Carbon\Carbon::parse($tglSelesai);

        $durasi = ceil($start->diffInDays($end) / 7); // Hitung minggu
        $set('durasi_proyek', max($durasi, 1)); // Minimal 1 minggu
    }
}
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pekerjaan'),
                //Tables\Columns\TextColumn::make('jenis_pekerjaan'),
                Tables\Columns\TextColumn::make('nama_klien'),
                Tables\Columns\TextColumn::make('lokasi'),
                //Tables\Columns\TextColumn::make('tahap_pengerjaan'),
                Tables\Columns\TextColumn::make('note')->label('Catatan'),
                Tables\Columns\TextColumn::make('total_volume'),                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'Completed' => 'success',
                        'In Progress' => 'warning',
                        'Pending' => 'danger',
                        'On Hold' => 'gray',
                        default => 'secondary',
                    }),
                //Tables\Columns\TextColumn::make('durasi_proyek')->label('Durasi (Minggu)'),
                //Tables\Columns\TextColumn::make('jumlah_sdm')->label('Jumlah SDM'),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->hidden()
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->label('Lampiran')
                    ->hidden()
                    ->formatStateUsing(fn ($state) => 'Lihat PDF')
                    ->action('preview_pdf'), // Tambahkan action yang sudah kita buat                          
                Tables\Columns\TextColumn::make('dokumentasi_foto')->label('Foto Absen')->hidden()->url(fn ($record) => implode('<br>', self::getDokumentasiFotoUrls($record))),                
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')

                ->label('Approve')
                ->color('success')
                ->action(function ($record) {
                    if ($record) {
                        $record->update(['status' => 'Completed']);
                    }
                })
                ->visible(fn ($record) => $record && $record->status === 'In Progress'),
                Tables\Actions\Action::make('reject')
                    ->form([
                        Forms\Components\TextInput::make('note')
                            ->label('Note for Stage')
                            ->required(),
                    ])
                    ->icon('heroicon-o-x-mark')
                    ->label('Reject')
                    ->color('danger')
                    ->action(function ($record, $data) {
                        if ($record) {
                            $record->update(['status' => 'Pending', 'note' => $data['note']]);
                        }
                    })
                    ->visible(fn ($record) => $record && $record->status === 'In Progress'),
                Tables\Actions\EditAction::make(), 
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('preview_pdf')
                    ->label('Lihat PDF')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading('Preview Lampiran')
                    ->modalWidth('max-w-7xl') // Lebih fleksibel
                    ->modalContent(fn ($record) => view('components.pdf-viewer', [
                        'pdfUrl' => self::getLampiranUrl($record),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),      
                Tables\Actions\Action::make('preview_images')
                    ->label('Lihat Foto')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->modalHeading('Preview Dokumentasi')
                    ->modalWidth('max-w-4xl') // Ukuran modal lebih besar agar gambar terlihat jelas
                    ->modalContent(fn ($record) => view('components.image-viewer', [
                        'imageUrls' => self::getDokumentasiFotoUrls($record), // Ambil URL gambar dari helper function
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
      

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketing::route('/'),
            'create' => Pages\CreateMarketing::route('/create'),
            'edit' => Pages\EditMarketing::route('/{record}/edit'),
        ];
    }
}
