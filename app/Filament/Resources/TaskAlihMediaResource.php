<?php

namespace App\Filament\Resources;

use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use Filament\Tables\Columns\TextColumn;
use App\Models\TaskAlihMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TaskAlihMediaResource\Pages;
use App\Models\User;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Http;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Marketing;
use App\Filament\Resources\TaskAlihMediaResource\RelationManagers\TaskWeekAlihMediaRelationManager;
use App\Filament\Resources\TaskAlihMediaResource\RelationManagers\TaskDayAlihMediaRelationManager;

class TaskAlihMediaResource extends Resource
{
    protected static ?string $model = TaskAlihMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Proyek';
    protected static ?string $navigationGroup = 'Alih Media';
    protected static ?string $pluralLabel = 'Proyek';
    protected static ?int $navigationSort = 1; // Menentukan urutan menu
    // Menambahkan widget ke halaman TaskResource

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Kalau bukan super_admin, filter datanya
        if (!auth()->user()?->hasAnyRole(['super_admin', 'Manajer Keuangan', 'Manajer Operasional'])) {
            $query->where('project_manager', auth()->id());
        }

        return $query;
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('marketing_id')
                ->label('Pekerjaan dari marketing')
                ->required()
                ->default(fn ($get, $state) => $state ?? $get('record.marketing_id'))
                ->preload()
                ->disabled(fn ($get, $record) => $record !== null)
                ->live()
                ->extraAttributes(['id' => 'marketing_id']) // Tambahkan ID untuk JavaScript
                ->options(function ($get, $record) {
                    $query = Marketing::query()
                        ->where('jenis_pekerjaan', 'Alih Media')
                        ->where(function ($query) use ($record) {
                            $query->where('status', 'Persiapan Operasional');

                            if ($record && $record->marketing_id) {
                                $query->orWhere('id', $record->marketing_id);
                            }
                        })
                        ->where('project_manager', auth()->user()->id);

                    return $query->pluck('nama_pekerjaan', 'id');
                })       
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $marketing = Marketing::find($state);
                    $user = User::find($state);
                    if ($marketing) {
                        $set('pekerjaan', $marketing->nama_pekerjaan);
                        $set('klien', $marketing->nama_klien);
                        $set('lokasi', $marketing->lokasi);              
                        $set('nilai_proyek', $marketing->nilai_akhir_proyek);
                        $set('link_rab', $marketing->link_rab);                    
                        $set('volume_arsip', $marketing->total_volume);  
                        $set('marketing_note_operasional', $marketing->note_operasional);                    
                        $set('status', 'Behind Schedule');
                        $set('tahap_pengerjaan', 'Scanning');

                        // Panggil update target setelah marketing_id diubah
                        self::updateDurasiDanLamaPekerjaan($set, $get);
                        self::updateTargetPerminggu($set, $get);
                    }
                }),

            Forms\Components\TextInput::make('pekerjaan')
                ->label('Pekerjaan')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('klien')
                ->label('Klien')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('volume_arsip')
                ->label('Volume Arsip (Halaman)')
                ->prefix('mL ')
                ->inputMode('decimal')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('tgl_mulai')
                ->label('Tanggal Mulai')
                ->live()
                ->required()
                ->default(now())
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    self::updateDurasiDanLamaPekerjaan($set, $get);
                    self::updateTargetPerminggu($set, $get);
                }),

            Forms\Components\DatePicker::make('tgl_selesai')
                ->label('Tanggal Selesai')
                ->required()
                ->live()
                ->default(now())
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    self::updateDurasiDanLamaPekerjaan($set, $get);
                    self::updateTargetPerminggu($set, $get);
                }),

            Forms\Components\Select::make('tahap_pengerjaan')
                ->label('Tahap Pengerjaan')
                ->required()
                ->hidden()
                ->options(\App\Models\JenisTaskAlihMedia::pluck('nama_task', 'nama_task'))
                ->default('Scanning')
                ->dehydrated(true), // Pastikan nilai ini ikut dikirim saat submit

            Forms\Components\Select::make('status')
                ->label('Status')
                ->disabled()
                ->default('Behind Schedule')
                ->options([
                    'On Track' => 'On Track',
                    'Behind Schedule' => 'Behind Schedule',
                    'Far Behind Schedule' => 'Far Behind Schedule',
                    'Complete' => 'Completed',
                ])
                ->required(),

            Forms\Components\Select::make('resiko_keterlambatan')
                ->label('Resiko Keterlambatan')
                ->disabled()
                ->options([
                    'Low' => 'Low',
                    'Medium' => 'Medium',
                    'High' => 'High',
                    'Completed' => 'Completed',
                ])
                ->default('Low'),

            Forms\Components\TextInput::make('durasi_proyek')
                ->label('Durasi Proyek (Minggu)')
                ->numeric()
                ->disabled()
                ->default(fn ($get) => static::calculateDuration($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateDuration($get))
                ->required(),

            Forms\Components\Hidden::make('nilai_proyek'),
            Forms\Components\TextInput::make('lama_pekerjaan')
                ->label('Lama Pekerjaan (Hari)')
                ->numeric()
                ->hidden()
                ->disabled()
                ->default(fn ($get) => static::calculateLamaPekerjaan($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateLamaPekerjaan($get))
                ->required(),
            Forms\Components\TextInput::make('total_hari_kerja')
                ->label('Total Hari Kerja')
                ->numeric()
                ->disabled()
                ->default(fn ($get) => static::calculateTotalHariKerja($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateTotalHariKerja($get))
                ->required(),

            Forms\Components\TextInput::make('target_perminggu')
                ->label('Target Perminggu (Halaman)')
                ->numeric()
                ->inputMode('decimal')
                ->disabled()
                ->default(fn ($get) => static::calculateTargetPerminggu($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateTargetPerminggu($get))
                ->dehydrated()
                ->required(),

            Forms\Components\TextInput::make('target_perday')
                ->label('Target Perhari (Halaman)')
                ->numeric()
                ->disabled()
                ->inputMode('decimal')
                ->default(fn ($get) => static::calculateTargetPerDay($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateTargetPerDay($get))
                ->dehydrated()
                ->required(),

            Forms\Components\Select::make('project_manager')
                        ->relationship('user', 'name')
                        ->default(auth()->id()) // otomatis isi user yang login
                        ->disabled()            // supaya tidak bisa diganti
                        ->dehydrated()          // tetap dikirim ke server saat submit form
                        ->required()
                        ->label('Project Manager'),       

            Forms\Components\TextInput::make('no_telp_pm')
                        ->label('No Telp. PM')
                        ->tel()
                        ->default(auth()->user()?->Telepon) // safe access
                        ->disabled()
                        ->dehydrated()
                        ->required(),                    

            Forms\Components\TextInput::make('link_rab')
                ->label('Link RAB')
                ->url()
                ->nullable(),

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

            Forms\Components\Select::make('jenis_arsip')
                ->label('Jenis Arsip')
                ->options([
                    'Aktif' => 'Aktif',
                    'Inaktif' => 'Inaktif',
                    'Campuran' => 'Campuran',
                ])
                ->required(),
                Forms\Components\TextInput::make('jumlah_sdm')
                    ->label('Jumlah SDM')
                    ->numeric()
                    ->required(),

            Forms\Components\Textarea::make('deskripsi_pekerjaan')
                ->label('Deskripsi Pekerjaan')
                ->rows(3)
                ->required(),


            Forms\Components\Repeater::make('pelaksana')
                ->label('Pelaksana')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Pelaksana')
                        ->required()
                        ->maxLength(255),
                ])
                ->minItems(1)
                ->maxItems(fn ($get) => $get('jumlah_sdm'))
                ->columns(1)
                ->addable(true)
                ->deletable(true)
                ->default([])
                ->required(),
            Forms\Components\Textarea::make('marketing_note_operasional')
                ->label('Catatan Operasional Marketing')
                ->rows(5)
                ->disabled()
                ->dehydrated(false)
                ->afterStateHydrated(function (callable $set, $state, $get, $record) {
                    if ($record?->marketing) {
                        $set('marketing_note_operasional', $record->marketing->note_operasional);
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('pekerjaan')
                ->label('Pekerjaan')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('klien')
                ->label('Klien')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('tahap_pengerjaan')
                ->label('Tahap Pengerjaan'),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->sortable()
                ->color(fn ($state) => match ($state) {
                    'On Track' => 'success',
                    'Behind Schedule' => 'warning',
                    'Far Behind Schedule' => 'danger',
                    'Completed' => 'gray',
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

            Tables\Columns\TextColumn::make('durasi_proyek')
                ->label('Durasi (Minggu)')
                ->sortable(),

            Tables\Columns\TextColumn::make('lama_pekerjaan')
                ->label('Lama Pekerjaan (Hari)')
                ->sortable(),
            Tables\Columns\TextColumn::make('total_hari_kerja')
                ->label('Total Hari Kerja')
                ->sortable(),
            Tables\Columns\TextColumn::make('jumlah_sdm')
                ->label('Tot. SDM')
                ->sortable(),

            Tables\Columns\TextColumn::make('user.name')
                ->label('Project Manager')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('no_telp_pm')
                ->label('No Telp. PM'),

            Tables\Columns\TextColumn::make('tgl_mulai')
                ->label('Tanggal Mulai')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('tgl_selesai')
                ->label('Tanggal Selesai')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('lokasi')
                ->label('Lokasi')
                ->sortable(),

            Tables\Columns\TextColumn::make('volume_arsip')
                ->label('Total Volume (Halaman)')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable(),
            
            Tables\Columns\TextColumn::make('volume_dikerjakan')
                ->label('Volume Dikerjakan (Halaman)')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable(),

            ProgressBar::make('dikerjakan_step1')
            ->label('Scanning')
                ->getStateUsing(function ($record) {
                    $total = $record->volume_arsip;
                    $progress = $record->dikerjakan_step1;
                    return [
                        'total' => $total,
                        'progress' => $progress,
                    ];
                })
                ->extraAttributes(['class' => 'progress-bar-hidden']),

            ProgressBar::make('dikerjakan_step2')
                ->label('Quality Control')
                ->getStateUsing(function ($record) {
                    $total = $record->volume_arsip;
                    $progress = $record->dikerjakan_step2;
                    return [
                        'total' => $total,
                        'progress' => $progress,
                    ];
                })
                ->extraAttributes(['class' => 'progress-bar-hidden']),

            ProgressBar::make('dikerjakan_step3')
                ->label('Input Data')
                ->getStateUsing(function ($record) {
                    $total = $record->volume_arsip;
                    $progress = $record->dikerjakan_step3;
                    return [
                        'total' => $total,
                        'progress' => $progress,
                    ];
                })
                ->extraAttributes(['class' => 'progress-bar-hidden']),

            ProgressBar::make('dikerjakan_step4')
                ->label('Upload Data Hyperlink')
                ->getStateUsing(function ($record) {
                    $total = $record->volume_arsip;
                    $progress = $record->dikerjakan_step4;
                    return [
                        'total' => $total,
                        'progress' => $progress,
                    ];
                })
                ->extraAttributes(['class' => 'progress-bar-hidden']),

            Tables\Columns\TextColumn::make('jenis_arsip')
                ->label('Jenis Arsip')
                ->sortable(),

            Tables\Columns\TextColumn::make('deskripsi_pekerjaan')
                ->label('Deskripsi Pekerjaan')
                ->limit(50),

            Tables\Columns\TextColumn::make('target_perminggu')
                ->label('Target Perminggu (Halaman)')
                ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->sortable(),

            Tables\Columns\TextColumn::make('target_perday')
            ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )
                ->label('Target Perhari (Halaman)')
                ->sortable(),
            ])
            ->filters([
                SelectFilter::make('lokasi')
                    ->label('Filter Lokasi')
                    ->searchable()
                    ->options(fn () => TaskAlihMedia::query()->distinct()->pluck('lokasi', 'lokasi')->toArray()),
                SelectFilter::make('pekerjaan')
                    ->label('Filter Pekerjaan')
                    ->searchable()
                    ->options(fn () => TaskAlihMedia::query()->distinct()->pluck('pekerjaan', 'pekerjaan')->toArray()),
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->searchable()
                    ->options(fn () => TaskAlihMedia::query()->distinct()->pluck('status', 'status')->toArray()),
                SelectFilter::make('resiko_keterlambatan')
                    ->label('Filter Resiko')
                    ->searchable()
                    ->options(fn () => TaskAlihMedia::query()->distinct()->pluck('resiko_keterlambatan', 'resiko_keterlambatan')->toArray()),
                SelectFilter::make('tahap_pengerjaan')
                    ->label('Filter Tahap')
                    ->searchable()
                    ->options(fn () => TaskAlihMedia::query()->distinct()->pluck('tahap_pengerjaan', 'tahap_pengerjaan')->toArray()),
                SelectFilter::make('project_manager')
                    ->label('Filter PM')
                    ->searchable()
                    ->options(fn () => \App\Models\User::pluck('name', 'id')->toArray()),
                SelectFilter::make('jenis_arsip')
                    ->label('Filter Jenis Arsip')
                    ->searchable()
                    ->options(fn () => TaskAlihMedia::query()->distinct()->pluck('jenis_arsip', 'jenis_arsip')->toArray()),
                
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Print')
                ->url(fn ($record) => url('/task-alih-media/' . $record->id . '/print'))
                ->icon('heroicon-o-printer')
                ->openUrlInNewTab(),
                    ])
                    ->bulkActions([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]);
    }

    public static function getRelations(): array
    {
        return [
            TaskWeekAlihMediaRelationManager::class,  
            TaskDayAlihMediaRelationManager::class,            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskAlihMedia::route('/'),
            'create' => Pages\CreateTaskAlihMedia::route('/create'),
            'edit' => Pages\EditTaskAlihMedia::route('/{record}/edit'),
        ];
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
    $periode = CarbonPeriod::create($start, $end);

    // Ambil semua tahun yang dicakup
    $years = range($start->year, $end->year);
    $tanggalMerah = [];

    // Ambil semua tanggal merah
    foreach ($years as $year) {
        $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data as $item) {
                $tanggalMerah[] = Carbon::parse($item['tanggal'])->toDateString();
            }
        }
    }

    // Hitung minggu ke-n yang memiliki hari kerja
    $mingguAktif = [];

    foreach ($periode as $tanggal) {
        if (
            $tanggal->dayOfWeek !== Carbon::SUNDAY &&
            !in_array($tanggal->toDateString(), $tanggalMerah)
        ) {
            $mingguKe = intval($start->diffInWeeks($tanggal)) + 1;
            $mingguAktif[$mingguKe] = true; // gunakan array sebagai set
        }
    }

    return count($mingguAktif); // jumlah minggu yang punya hari kerja
}

    public static function calculateLamaPekerjaan($get)
    {
        $tglMulai = $get('tgl_mulai');
        $tglSelesai = $get('tgl_selesai');

        if (!$tglMulai || !$tglSelesai) {
            return 0;
        }

        $start = Carbon::parse($tglMulai);
        $end = Carbon::parse($tglSelesai);

        return $start->diffInDays($end);
    }

    public static function updateDurasiDanLamaPekerjaan(callable $set, $get)
    {
        $set('durasi_proyek', self::calculateDuration($get));
        $set('lama_pekerjaan', self::calculateLamaPekerjaan($get));
        $set('total_hari_kerja', self::calculateTotalHariKerja($get));
    }

    public static function calculateTargetPerminggu($get)
    {
        $jumlahTahap = \App\Models\JenisTaskAlihMedia::count();
        $volumeArsip = (float) $get('volume_arsip') * $jumlahTahap;
        $durasiProyek = (int) $get('durasi_proyek');

        if ($durasiProyek <= 0) {
            return 0;
        }

        return round($volumeArsip / $durasiProyek, 2);
    }

    public static function calculateTargetPerDay($get)
    {
        $jumlahTahap = \App\Models\JenisTaskAlihMedia::count();
        $volumeArsip = (float) $get('volume_arsip')*$jumlahTahap;
        $lamaPekerjaan = (int) $get('total_hari_kerja');

        if ($lamaPekerjaan <= 0) {
            return 0;
        }

        return round($volumeArsip / $lamaPekerjaan, 2);
    }

    /**
     * Memperbarui nilai Target Perminggu saat Volume Arsip atau Durasi Proyek berubah
     */
    public static function updateTargetPerminggu(callable $set, $get)
    {
        $set('target_perminggu', self::calculateTargetPerminggu($get));
        $set('target_perday', self::calculateTargetPerDay($get));
    }

    /**
     * Override metode create untuk mengubah status marketing menjadi "on hold"
     */
    public static function create(array $data): TaskAlihMedia
    {
        $task = TaskAlihMedia::create($data);

        // Update status marketing menjadi "On Hold" dan log status
        Log::info('Updating marketing status to On Hold for marketing_id: ' . $data['marketing_id']);
        if (isset($data['marketing_id'])) {
            $marketing = Marketing::find($data['marketing_id']);
            if ($marketing) {
                $marketing->status = 'Pengerjaan'; // Ubah status
                $marketing->save(); // Simpan perubahan
            }
        }

        return $task;
    }

    /**
     * Override metode update untuk mengubah status marketing menjadi "on hold"
     */
    public static function update(array $data, TaskAlihMedia $record): TaskAlihMedia
    {
        $record->update($data);

        // Update status marketing menjadi "On Hold" dan log status
        Log::info('Updating marketing status to On Hold for marketing_id: ' . $data['marketing_id']);
        if (isset($data['marketing_id'])) {
            $marketing = Marketing::find($data['marketing_id']);
            if ($marketing) {
                $marketing->status = 'Pengerjaan'; // Ubah status
                $marketing->save(); // Simpan perubahan
            }
        }

        return $record;
    }
    public static function calculateTotalHariKerja($get): int
{
    if (!$get('tgl_mulai') || !$get('tgl_selesai')) {
        return 0;
    }

    $start = Carbon::parse($get('tgl_mulai'));
    $end = Carbon::parse($get('tgl_selesai'));
    $periode = CarbonPeriod::create($start, $end);

    $years = range($start->year, $end->year);
    $tanggalMerah = [];

    
    // Ambil semua tanggal libur dari API (baik is_cuti true maupun false)
    foreach ($years as $year) {
        $response = Http::get("https://dayoffapi.vercel.app/api?year={$year}");

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data as $item) {
                $tanggalMerah[] = Carbon::parse($item['tanggal'])->toDateString(); // Normalisasi format
            }
        }
    }

    $hariKerja = 0;

    foreach ($periode as $tanggal) {
        // Hitung jika bukan Minggu DAN bukan tanggal merah
        if (
            $tanggal->dayOfWeek !== Carbon::SUNDAY &&
            !in_array($tanggal->toDateString(), $tanggalMerah)
        ) {
            $hariKerja++;
        }
    }

    return $hariKerja;
}
}
