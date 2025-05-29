<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingResource\Pages;
use App\Models\Marketing;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\IconColumn;
use Carbon\Carbon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Models\User;
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
    protected static ?string $pluralLabel = 'Proyek Marketing'; // Header
    protected static ?string $navigationLabel = 'Proyek'; // Menu Tittle

    //untuk akses sesuai project manager
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Kalau bukan super_admin, filter datanya
        if (!auth()->user()?->hasAnyRole(['super_admin', 'Manajer Keuangan', 'Manajer Operasional'])) {
            $query->where('nama_pic', auth()->id());
        }

        return $query;
    }


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
                        'Penyusunan Instrumen' => 'Penyusunan Instrumen',
                        'Pengadaan Barang' => 'Pengadaan Barang',
                    ]
                )->label('Jenis Pekerjaan'),
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
                Forms\Components\TextInput::make('total_volume')->label('Volume Pekerjaan')->numeric(),
                Forms\Components\Select::make('nama_pic')
                        ->relationship('manager', 'name')
                        ->default(auth()->id()) // otomatis isi user yang login
                        ->disabled()            // supaya tidak bisa diganti
                        ->dehydrated()          // tetap dikirim ke server saat submit form
                        ->required()
                        ->label('Nama PIC'),
                Forms\Components\Select::make('project_manager')
                        ->relationship('manager', 'name')
                        ->label('Project Manager'),
                Forms\Components\Select::make('status')->options([
                        'Ditolak' => 'Ditolak',
                        'In Progress' => 'In Progress',
                        'Pengajuan' => 'Pengajuan',
                        ])->required()->label('Status Pekerjaan')->default('Ditolak')
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state === 'Pengajuan') {
                                // Logic for when status is set to Pengajuan
                            }
                            if ($state === 'Ditolak') {
                                // Logic for when status is set to Ditolak
                            }
                        }),
                // Forms\Components\TextInput::make('durasi_proyek')
                //         ->label('Durasi Proyek (Minggu)')
                //         ->numeric()
                //         ->disabled()
                //         ->dehydrated()
                //         ->default(fn ($get) => static::calculateDuration($get('tgl_mulai'), $get('tgl_selesai')))
                //         ->formatStateUsing(fn ($state, $get) => static::calculateDuration($get('tgl_mulai'), $get('tgl_selesai')))
                //         ->required(),
                    
                Forms\Components\TextInput::make('jumlah_sdm')->required()->label('Jumlah SDM')->numeric(),
                Forms\Components\TextInput::make('nilai_proyek')->required()->label('Nilai Penawaran')->numeric()->prefix('Rp '),
                Forms\Components\TextInput::make('link_rab')->nullable()->label('Link RAB'), 
                // Forms\Components\DatePicker::make('tgl_mulai')
                //         ->label('Tanggal Mulai')
                //         ->required()
                //         ->default(now())
                //         ->reactive()
                //         ->afterStateUpdated(function ($state, $set, $get) {
                //             $set('durasi_proyek', static::calculateDuration(
                //                 $state, // tanggal mulai baru
                //                 $get('tgl_selesai') // tanggal selesai sekarang
                //             ));
                //         }),
            
                // Forms\Components\DatePicker::make('tgl_selesai')
                //         ->label('Tanggal Selesai')
                //         ->required()
                //         ->default(now()->addDays(7))
                //         ->reactive()
                //         ->afterStateUpdated(function ($state, $set, $get) {
                //             $set('durasi_proyek', static::calculateDuration(
                //                 $get('tgl_mulai'), // tanggal mulai sekarang
                //                 $state // tanggal selesai baru
                //             ));
                //         }),
                // Forms\Components\TextInput::make('nilai_akhir_proyek')->label('Nilai Akhir Proyek')->numeric()->prefix('Rp ')(),
                // Forms\Components\TextInput::make('terms_of_payment')->label('Terms of Payment')->numeric()->prefix('Day ')->required()->default(60),
                // Forms\Components\Select::make('status_pembayaran')
                //         ->options([
                //             'Belum Lunas' => 'Belum Lunas',
                //             'Lunas' => 'Lunas',
                //         ])
                //         ->required()
                //         ->label('Status Pembayaran')
                //         ->default('Belum Lunas'),

                Forms\Components\FileUpload::make('dokumentasi_foto')
                        ->multiple()
                        ->directory('marketing_foto')
                        ->image()
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, callable $get): string {
                            // Jika sedang edit, gunakan ID dari model; jika baru, buat UUID unik
                            $marketingId = $get('id') ?? Str::uuid()->toString();
                    
                            // Gunakan session untuk menyimpan counter per sesi unggahan
                            $counter = session()->increment("upload_counter_{$marketingId}", 1);
                    
                            return "marketing_{$marketingId}_fotoabsen_{$counter}." . $file->getClientOriginalExtension();
                        }),
                Forms\Components\FileUpload::make('lampiran')->acceptedFileTypes(['application/pdf'])->directory('marketing_lampiran')->helperText('Upload Form Suvey'),
                Forms\Components\Textarea::make('note')->label('Catatan'),
                Forms\Components\Textarea::make('note_operasional')->label('Catatan Operasional'),
            ]);
    }

    public static function getLampiranUrl($record): string
    {
        return asset('ProjectTrackerIndoraj/storage/app/public/' . $record->lampiran);
        //return asset('storage/' . $record->lampiran);
    }

    public static function getDokumentasiFotoUrls($record): array
    {
        return array_map(function ($foto) {
            return asset('ProjectTrackerIndoraj/storage/app/public/' . $foto);
            //return asset('storage/' . $foto);
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
                Tables\Columns\TextColumn::make('total_volume')->sortable()
                ->label('Volume')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        ),                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->color(fn ($state) => match ($state) {
                        'Pengajuan' => 'success',
                        'In Progress' => 'warning',
                        'Ditolak' => 'danger',
                        'Pengerjaan' => 'gray',
                        'Completed' => 'gray',
                        'Persiapan Operasional' => 'gray',
                        default => 'secondary',
                    }),
                //Tables\Columns\TextColumn::make('durasi_proyek')->label('Durasi (Minggu)'),
                //Tables\Columns\TextColumn::make('jumlah_sdm')->label('Jumlah SDM'),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->hidden()
                    ->date()
                    ->sortable(),
              Tables\Columns\TextColumn::make('nilai_proyek')
                    ->label('Nilai Penawaran')
                    ->hidden(fn () => !auth()->user()?->hasAnyRole(['Manajer Keuangan', 'super_admin']))
                    ->money(
                        currency: 'IDR',
                        locale: 'id', // Format Indonesia
                    )
                    ->sortable(),                    
                Tables\Columns\TextColumn::make('nilai_akhir_proyek')
                    ->label('Nilai Proyek')
                    ->hidden(fn () => !auth()->user()?->hasAnyRole(['Manajer Keuangan', 'super_admin']))
                    ->money(
                        currency: 'IDR',
                        locale: 'id', // Format Indonesia
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('dasar_pengenaan_pajak')
                    ->label('DPP')
                    ->hidden(fn () => !auth()->user()?->hasAnyRole(['Manajer Keuangan', 'super_admin']))
                    ->money(
                        currency: 'IDR',
                        locale: 'id', // Format Indonesia
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('ppn')
                    ->label('PPN')
                    ->hidden(fn () => !auth()->user()?->hasAnyRole(['Manajer Keuangan', 'super_admin']))
                    ->money(
                        currency: 'IDR',
                        locale: 'id', // Format Indonesia
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('pph')
                    ->label('PPH')
                    ->hidden(fn () => !auth()->user()?->hasAnyRole(['Manajer Keuangan', 'super_admin']))
                    ->money(
                        currency: 'IDR',
                        locale: 'id', // Format Indonesia
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('pencairan')
                    ->label('Pencairan')
                    ->hidden(fn () => !auth()->user()?->hasAnyRole(['Manajer Keuangan', 'super_admin']))
                    ->money(
                        currency: 'IDR',
                        locale: 'id', // Format Indonesia
                    )
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
                Tables\Columns\IconColumn::make('manajer_keuangan')
                    ->label('Manajer Keuangan')
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
    ->visible(fn ($record) => 
        $record
        && $record->tahap_pengerjaan === 'Kontrak'
        && $record->status === 'Pengajuan'
        && (
            (auth()->user()?->hasRole('Manajer Operasional') && $record->manajer_operasional == 0) ||
            (auth()->user()?->hasRole('Manajer Keuangan') && $record->manajer_keuangan == 0)
        )
    )
->form(fn () => auth()->user()?->hasRole('Manajer Keuangan') ? [
        Forms\Components\TextInput::make('nilai_akhir_proyek')
    ->label('Nilai Kontrak (Termasuk PPN)')
    ->numeric()
    ->prefix('Rp ')
    ->required()
    ->default(fn ($record) => $record?->nilai_akhir_proyek ?? $record?->nilai_proyek ?? 0)
    ->live()
    ->afterStateUpdated(function ($state, $set, $get) {
        $settings = \App\Models\Setting::first();
        $ppnPercentage = $settings->ppn ?? 11;

        // Ambil dari record jika 'jenis_pekerjaan' tidak merupakan form field
        $record = $get(null); // atau request()->route('record')
        $jenisPekerjaan = $get('jenis_pekerjaan') ?? $record?->jenis_pekerjaan;

        $pphPercentage = ($jenisPekerjaan === 'Pengadaan Barang')
            ? ($settings->pph_barang ?? 1.5)
            : ($settings->pph ?? 2);

        // Format angka dari input string
        $nilaiTermasukPPN = (float) str_replace(',', '', $state);

        // Perhitungan
        $dasarPengenaanPajak = round(($nilaiTermasukPPN * 100) / (100 + $ppnPercentage), 2);
        $ppn = round($dasarPengenaanPajak * ($ppnPercentage / 100), 2);
        $pph = round($dasarPengenaanPajak * ($pphPercentage / 100), 2);
        $pencairan = round($dasarPengenaanPajak - $pph, 2);

        // Set hasil ke field lain
        $set('dasar_pengenaan_pajak', $dasarPengenaanPajak);
        $set('ppn', $ppn);
        $set('pph', $pph);
        $set('pencairan', $pencairan);
    }),
    Forms\Components\TextInput::make('dasar_pengenaan_pajak')
        ->label('Dasar Pengenaan Pajak (DPP)')
        ->numeric()
        ->prefix('Rp ')
        ->default(function ($record) {
            $settings = \App\Models\Setting::first();
            $nilaiTermasukPPN = $record?->nilai_akhir_proyek ?? $record?->nilai_proyek ?? 0;
            return number_format(($nilaiTermasukPPN / (100 + ($settings->ppn ?? 11))) * 100, 2, '.', '');
        }),

    Forms\Components\TextInput::make('ppn')
        ->label('PPN')
        ->numeric()
        ->prefix('Rp ')
        ->required()
        ->default(function ($record) {
            $settings = \App\Models\Setting::first();
            $nilaiTermasukPPN = $record?->nilai_akhir_proyek ?? $record?->nilai_proyek ?? 0;
            $dpp = ($nilaiTermasukPPN / (100 + ($settings->ppn ?? 11))) * 100;
            return number_format($dpp * (($settings->ppn ?? 11) / 100), 2, '.', '');
        }),

Forms\Components\TextInput::make('pph')
    ->label('PPH')
    ->numeric()
    ->prefix('Rp ')
    ->required()
    ->default(function ($record) {
        $settings = \App\Models\Setting::first();
        $nilaiTermasukPPN = $record?->nilai_akhir_proyek ?? $record?->nilai_proyek ?? 0;
        $jenisPekerjaan = $record?->jenis_pekerjaan ?? '';

        $ppnPercentage = $settings->ppn ?? 11;
        $pphPercentage = ($jenisPekerjaan === 'Pengadaan Barang')
            ? ($settings->pph_barang ?? 1.5)
            : ($settings->pph ?? 2);

        $dpp = ($nilaiTermasukPPN / (100 + $ppnPercentage)) * 100;
        return number_format($dpp * ($pphPercentage / 100), 2, '.', '');
    }),
    Forms\Components\TextInput::make('pencairan')
    ->label('Nilai Pencairan (DPP - PPH)')
    ->numeric()
    ->prefix('Rp ')
    ->required()
    ->default(function ($record) {
        $settings = \App\Models\Setting::first();
        $nilaiTermasukPPN = $record?->nilai_akhir_proyek ?? $record?->nilai_proyek ?? 0;
        $jenisPekerjaan = $record?->jenis_pekerjaan ?? '';

        $ppnPercentage = $settings->ppn ?? 11;
        $pphPercentage = ($jenisPekerjaan === 'Pengadaan Barang')
            ? ($settings->pph_barang ?? 1.5)
            : ($settings->pph ?? 2);

        $dpp = ($nilaiTermasukPPN / (100 + $ppnPercentage)) * 100;
        $pph = $dpp * ($pphPercentage / 100);
        return number_format($dpp - $pph, 2, '.', '');
    }),
    
    Forms\Components\TextInput::make('terms_of_payment')
        ->label('Terms of Payment')
        ->numeric()
        ->prefix('Day ')
        ->required()
        ->default(60),
    
    Forms\Components\Select::make('status_pembayaran')
        ->options([
            'Belum Lunas' => 'Belum Lunas',
            'Lunas' => 'Lunas',
        ])
        ->required()
        ->label('Status Pembayaran')
        ->default('Belum Lunas'),
] : [])
    ->action(function ($record, array $data) {
        $user = auth()->user();

        if ($record && $user) {
            if ($user->hasRole('Manajer Operasional')) {
                $record->update([
                    'manajer_operasional' => 1,
                ]);
            } elseif ($user->hasRole('Manajer Keuangan')) {
                $record->update([
                    'manajer_keuangan' => 1,
                    'nilai_akhir_proyek' => $data['nilai_akhir_proyek'] ?? null,
                    'ppn' => $data['ppn'] ?? null,
                    'pph' => $data['pph'] ?? null,
                    'dasar_pengenaan_pajak' => $data['dasar_pengenaan_pajak'] ?? null,
                    'pencairan' => $data['pencairan'] ?? null,
                    'terms_of_payment' => $data['terms_of_payment'] ?? null,
                    'status_pembayaran' => $data['status_pembayaran'] ?? null,
                ]);
            }

            // Jika keduanya sudah menyetujui
            if ($record->manajer_operasional == 1 && $record->manajer_keuangan == 1) {
                $record->update([
                    'status' => 'Persiapan Operasional',
                ]);
            }
        }
    }),  

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
                ->modalSubmitActionLabel('Ya, Tolak')
                ->visible(fn ($record) => 
                $record
                && $record->tahap_pengerjaan === 'Kontrak'
                && $record->status === 'Pengajuan'
                && (
                    (auth()->user()?->hasRole('Manajer Operasional') && $record->manajer_operasional == 0) ||
                    (auth()->user()?->hasRole('Manajer Keuangan') && $record->manajer_keuangan == 0)
                )
            )            
                ->action(function ($record, $data) {
                    $user = auth()->user();

                    if ($record && $user) {
                        $record->update([
                            'status' => 'Ditolak',
                            'note' => $data['note'] ?? null, // Kalau ada note dari form
                            'manajer_operasional' => 0,
                            'manajer_keuangan' => 0,
                        ]);
                    }
                }),

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
    
    public static function calculateDuration($tglMulai, $tglSelesai)
    {
        if (!$tglMulai || !$tglSelesai) {
            return 0;
        }
    
        $start = \Carbon\Carbon::parse($tglMulai);
        $end = \Carbon\Carbon::parse($tglSelesai);
    
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
