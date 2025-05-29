<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskBarangResource\Pages;
use App\Filament\Resources\TaskBarangResource\RelationManagers;
use App\Models\TaskBarang;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Models\Marketing;
use Filament\Tables;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Http;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskBarangResource extends Resource
{
    protected static ?string $model = TaskBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Pengadaan Barang';
    protected static ?string $navigationGroup = 'Lainnya';
    protected static ?string $pluralLabel = 'Pengadaan Barang';
    protected static ?int $navigationSort = 3; // Menentukan urutan menu
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
        return $form
            ->schema([
                 Forms\Components\Select::make('marketing_id')
                ->label('Pekerjaan dari marketing')
                ->required()
                ->preload()
                ->live()
                ->default(fn ($get, $record) => $get('marketing_id') ?? $record?->marketing_id)
                ->disabled(fn ($get, $record) => $record !== null)
                ->extraAttributes(['id' => 'marketing_id'])
                ->options(function ($get, $record) {
                    $query = Marketing::query()
                        ->where('jenis_pekerjaan', 'Pengadaan Barang')
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
                    $marketing = \App\Models\Marketing::find($state);
                    if ($marketing) {
                        $set('pekerjaan', $marketing->nama_pekerjaan);
                        $set('klien', $marketing->nama_klien);
                        $set('lokasi', $marketing->lokasi);                     
                        $set('nilai_proyek', $marketing->nilai_akhir_proyek);
                        $set('link_rab', $marketing->link_rab);                    
                        $set('volume_arsip', $marketing->total_volume);               
                        $set('marketing_note_operasional', $marketing->note_operasional);
                        $set('tahap_pengerjaan', 'Boks Arsip Besar');
                        self::updateDurasiDanLamaPekerjaan($set, $get);
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

            Forms\Components\Select::make('tahap_pengerjaan')
                ->label('Tahap Pengerjaan')
                ->required()
                ->options(\App\Models\JenisBarang::pluck('nama_task', 'nama_task'))
                ->default('Boks Arsip Besar'),

            Forms\Components\TextInput::make('volume_arsip')
                ->label('Jumlah Barang')
                ->prefix('Satuan ')
                ->numeric()
                ->inputMode('decimal')
                ->step(0.01) 
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', ''))
                ->required(),

            Forms\Components\DatePicker::make('tgl_mulai')
                ->label('Tanggal Mulai')
                ->required()
                ->live()
                ->default(now())
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    self::updateDurasiDanLamaPekerjaan($set, $get);
                }),
                
            Forms\Components\DatePicker::make('tgl_selesai')
                ->label('Tanggal Selesai')
                ->required()
                ->live()
                ->default(now()->addMonth())
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    self::updateDurasiDanLamaPekerjaan($set, $get);
                }),

            Forms\Components\Hidden::make('nilai_proyek'),


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

            Forms\Components\Textarea::make('alamat')
                ->label('Alamat')
                ->rows(3)
                ->required(),

                
            Forms\Components\Textarea::make('deskripsi_pekerjaan')
                ->label('Deskripsi Pekerjaan')
                ->rows(3)
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
                    ->label('Total Volume (Satuan)')
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )                    
                    ->sortable(),
                
               
                Tables\Columns\TextColumn::make('deskripsi_pekerjaan')
                    ->label('Deskripsi Pekerjaan')
                    ->limit(50),
               
            ])
            ->filters([
                SelectFilter::make('lokasi')
                    ->label('Filter Lokasi')
                    ->searchable()
                    ->options(fn () => TaskBarang::query()->distinct()->pluck('lokasi', 'lokasi')->toArray()),
                SelectFilter::make('pekerjaan')
                    ->label('Filter Pekerjaan')
                    ->searchable()
                    ->options(fn () => TaskBarang::query()->distinct()->pluck('pekerjaan', 'pekerjaan')->toArray()),
                SelectFilter::make('tahap_pengerjaan')
                    ->label('Filter Tahap')
                    ->searchable()
                    ->options(fn () => TaskBarang::query()->distinct()->pluck('tahap_pengerjaan', 'tahap_pengerjaan')->toArray()),
                SelectFilter::make('project_manager')
                    ->label('Filter PM')
                    ->searchable()
                    ->options(fn () => \App\Models\User::pluck('name', 'id')->toArray()),                
                
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),                
                    ])
                    ->bulkActions([
                        Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTaskBarangs::route('/'),
            'create' => Pages\CreateTaskBarang::route('/create'),
            'edit' => Pages\EditTaskBarang::route('/{record}/edit'),
        ];
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
   public static function updateDurasiDanLamaPekerjaan(callable $set, $get)
    {
        $set('durasi_proyek', self::calculateDuration($get));
        $set('lama_pekerjaan', self::calculateLamaPekerjaan($get));
        $set('total_hari_kerja', self::calculateTotalHariKerja($get));
    }
}
