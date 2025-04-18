<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingResource\Pages;
use App\Models\Marketing;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\IconColumn;
use Carbon\Carbon;
use Filament\Tables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // Pastikan ini ada!
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Resources\Resource;

/**
 * @method bool hasRole(string $role)
 * @method bool hasAnyRole(array|string ...$roles)
 * @method \Spatie\Permission\Models\Role[] getRoleNames()
 */
class MarketingResource extends Resource
{
    protected static ?string $model = Marketing::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Marketing';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pekerjaan')->required()->label('Nama Pekerjaan'),
                Forms\Components\Select::make('jenis_pekerjaan')->options(
                    [
                        'Pengolahan Arsip' => 'Pengolahan Arsip',
                        'Alih Media' => 'Alih Media',
                        'Fumigasi' => 'Fumigasi',
                        'Aplikasi' => 'Aplikasi',
                    ]
                )->required()->label('Jenis Pekerjaan'),
                Forms\Components\TextInput::make('nama_klien')->required()->label('Nama Klien'),
                //Forms\Components\TextInput::make('lokasi')->required(),
                Forms\Components\Select::make('lokasi')
                    ->label('Pilih Kota')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        $url = "https://alamat.thecloudalert.com/api/kabkota/get/";
                        $response = Http::get($url);
                
                        if ($response->successful()) {
                            $data = collect($response->json()['result']);
                
                            // Filter berdasarkan pencarian
                            if ($search) {
                                $data = $data->filter(fn($item) => stripos($item['text'], $search) !== false);
                            }
                
                            // Return dalam format key-value (id => nama)
                            return $data->mapWithKeys(fn($item) => [$item['text'] => $item['text']])->toArray();
                        }
                
                        return [];
                    })
                    ->required(),
                
                Forms\Components\Select::make('tahap_pengerjaan')->required()->default('Perkenalan')->options(
                    [
                        'Perkenalan' => 'Perkenalan',
                        'Follow Up' => 'Follow Up',
                        'Negosiasi' => 'Negosiasi',
                        'Kontrak' => 'Kontrak',
                    ]
                ),
                Forms\Components\TextInput::make('total_volume')->label('Volume Pekerjaan')->numeric()->required(),
                Forms\Components\Select::make('nama_pic')->relationship('pic', 'name')->required()->label('PIC Projek'),
                Forms\Components\Select::make('project_manager')->relationship('manager', 'name')->required()->label('Nama Project Manager'),
                Forms\Components\Select::make('status')->options([
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    //'Completed' => 'Completed',
                    //'On Hold' => 'On Hold',
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
                    ->disabled()
                    ->default(fn ($get) => static::calculateDuration($get))
                    ->formatStateUsing(fn ($state, $get) => static::calculateDuration($get))
                    ->afterStateUpdated(fn ($state, $set, $get) => $set('durasi_proyek', static::calculateDuration($get)))
                    ->required(),

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
                Forms\Components\TextInput::make('nilai_akhir_proyek')->label('Nilai Akhir Proyek')->numeric()->prefix('Rp '),
                Forms\Components\TextInput::make('terms_of_payment')->label('Terms of Payment')->numeric()->prefix('Day ')->required()->default(60),
                Forms\Components\Select::make('status_pembayaran')->options([
                    'Belum Lunas' => 'Belum Lunas',
                    'Lunas' => 'Lunas',
                ])->required()->label('Status Pembayaran')->default('Belum Lunas'),

                Forms\Components\FileUpload::make('dokumentasi_foto')
                    ->multiple()
                    ->required()
                    ->directory('marketing_foto')
                    ->image()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, callable $get): string {
                        // Jika sedang edit, gunakan ID dari model; jika baru, buat UUID unik
                        $marketingId = $get('id') ?? Str::uuid()->toString();
                
                        // Gunakan session untuk menyimpan counter per sesi unggahan
                        $counter = session()->increment("upload_counter_{$marketingId}", 1);
                
                        return "marketing_{$marketingId}_fotoabsen_{$counter}." . $file->getClientOriginalExtension();
                    }),
                Forms\Components\FileUpload::make('lampiran')->required()->acceptedFileTypes(['application/pdf'])->directory('marketing_lampiran'),
                Forms\Components\TextInput::make('note')->label('Catatan')->maxLength(255),
            ]);
    }

    public static function getLampiranUrl($record): string
    {
        //return asset('ProjectTrackerIndoraj/storage/app/public/' . $record->lampiran);
        return asset('storage/' . $record->lampiran);
    }

    public static function getDokumentasiFotoUrls($record): array
    {
        return array_map(function ($foto) {
            //return asset('ProjectTrackerIndoraj/storage/app/public/' . $foto);
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
                Tables\Columns\TextColumn::make('nama_pekerjaan')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('jenis_pekerjaan')->sortable()->searchable()->label('Jenis'),
                Tables\Columns\TextColumn::make('nama_klien')->searchable(),
                Tables\Columns\TextColumn::make('lokasi')->searchable(),
                Tables\Columns\TextColumn::make('tahap_pengerjaan')->searchable()->sortable()->label('Tahap'),
                Tables\Columns\TextColumn::make('note')->label('Catatan'),
                Tables\Columns\TextColumn::make('total_volume')->sortable()->label('Volume'),                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->searchable()
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
                Tables\Columns\IconColumn::make('manajer_operasional')
                    ->label('Manajer Operasional')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->label('Lampiran')
                    ->hidden()
                    ->formatStateUsing(fn ($state) => 'Lihat PDF')
                    ->action('preview_pdf'), // Tambahkan action yang sudah kita buat                          
                Tables\Columns\TextColumn::make('dokumentasi_foto')->hidden()->label('Foto Absen')->url(fn ($record) => implode('<br>', self::getDokumentasiFotoUrls($record))),                
            ])
              ->filters([
                SelectFilter::make('lokasi')
                    ->label('Filter Lokasi')
                    ->options(fn () => Marketing::query()->distinct()->pluck('lokasi', 'lokasi')->toArray()),
                SelectFilter::make('nama_pekerjaan')
                    ->label('Filter Pekerjaan')
                    ->searchable()
                    ->options(fn () => Marketing::query()->distinct()->pluck('nama_pekerjaan', 'nama_pekerjaan')->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')

                ->label('')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Persetujuan')
                ->modalDescription('Apakah Anda yakin ingin menyetujui pekerjaan ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Setujui')
                ->action(function ($record) {
                    if ($record) {
                        $record->update(['status' => 'Completed']);
                
                        /** @var \App\Models\User $user */
                        $user = auth()->user();
                
                        if ($user->hasRole('super_admin')) {
                            $record->update(['manajer_operasional' => '1']);
                        }
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
                    ->label('')
                    ->color('danger')
                                    ->requiresConfirmation()
                ->modalHeading('Konfirmasi Persetujuan')
                ->modalDescription('Apakah Anda yakin ingin menolak pekerjaan ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Setujui')
                    ->action(function ($record, $data) {
                        if ($record) {
                            $record->update(['status' => 'Pending', 'note' => $data['note']]);
                            $record->update(['manajer_operasional' => '0']);
                        }
                    })
                    ->visible(fn ($record) => $record && $record->status === 'In Progress'),
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
                Tables\Actions\ViewAction::make()->label(''),     
                Tables\Actions\EditAction::make()->label(''), 
                Tables\Actions\DeleteAction::make()->label(''),
      

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
        public static function calculateDuration($get)
    {
        $tglMulai = $get('tgl_mulai');
        $tglSelesai = $get('tgl_selesai');

        if (!$tglMulai || !$tglSelesai) {
            return 0;
        }

        $start = Carbon::parse($tglMulai);
        $end = Carbon::parse($tglSelesai);

        return ceil($start->diffInDays($end) / 7);
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
