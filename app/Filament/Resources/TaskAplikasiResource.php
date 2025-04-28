<?php

namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TaskAplikasiResource\Pages;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Log;
use App\Models\Marketing;
use App\Filament\Resources\TaskAplikasiResource\RelationManagers\ReportAplikasiRelationManagers;
use App\Models\TaskAplikasi;

class TaskAplikasiResource extends Resource
{
    protected static ?string $model = TaskAplikasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Proyek';
    protected static ?string $navigationGroup = 'Proyek Aplikasi';
    protected static ?string $pluralLabel = 'Proyek';
    protected static ?int $navigationSort = 1; // Menentukan urutan menu

     public static function getRelations(): array
     {
         return [
             ReportAplikasiRelationManagers::class,
        ];
     }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('marketing_id')
                ->label('Pekerjaan dari marketing')
                ->required()
                ->default(fn ($get, $state) => $state ?? $get('record.marketing_id'))
                ->preload()
                ->live()
                ->extraAttributes(['id' => 'marketing_id']) // Tambahkan ID untuk JavaScript
                ->options(
                    Marketing::where('status', 'Completed')
                        ->where('jenis_pekerjaan', 'Aplikasi')
                        ->where('project_manager', auth()->user()->id)
                        ->pluck('nama_pekerjaan', 'id')
                )                
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $marketing = Marketing::find($state);
                    $user = User::find($state);
                    if ($marketing) {
                        $set('pekerjaan', $marketing->nama_pekerjaan);
                        $set('klien', $marketing->nama_klien);
                        $set('lokasi', $marketing->lokasi);
                        $set('tgl_mulai', $marketing->tgl_mulai);
                        $set('tgl_selesai', $marketing->tgl_selesai);
                        $set('nilai_proyek', $marketing->nilai_akhir_proyek);
                        $set('link_rab', $marketing->link_rab);
                        $set('volume', $marketing->total_volume);
                        $set('status', 'Behind Schedule');
                        $set('tahap_pengerjaan', 'Requirement Gathering');
                        
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

            Forms\Components\Select::make('tahap_pengerjaan')
                ->label('Tahap Pengerjaan')
                ->required()
                ->disabled()
                ->options(\App\Models\JenisTahapAplikasi::pluck('nama_task', 'nama_task'))
                ->default('Requirement Gathering') // Sesuaikan default yang valid
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

            Forms\Components\DatePicker::make('tgl_mulai')
                ->label('Tanggal Mulai')
                ->required()
                ->default(now())
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    self::updateDurasiDanLamaPekerjaan($set, $get);
                    self::updateTargetPerminggu($set, $get);
                }),

            Forms\Components\DatePicker::make('tgl_selesai')
                ->label('Tanggal Selesai')
                ->required()
                ->default(now())
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    self::updateDurasiDanLamaPekerjaan($set, $get);
                    self::updateTargetPerminggu($set, $get);
                }),

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
                ->disabled()
                ->default(fn ($get) => static::calculateLamaPekerjaan($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateLamaPekerjaan($get))
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

            Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi')
                ->required(),

            Forms\Components\TextInput::make('volume')
                ->label('Volume')
                ->prefix('Satuan')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('target_perminggu')
                ->label('Target Perminggu ')
                ->numeric()
                ->disabled()
                ->default(fn ($get) => static::calculateTargetPerminggu($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateTargetPerminggu($get))
                ->dehydrated()
                ->required(),

            Forms\Components\TextInput::make('target_perday')
                ->label('Target Perhari')
                ->numeric()
                ->disabled()
                ->default(fn ($get) => static::calculateTargetPerDay($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateTargetPerDay($get))
                ->dehydrated()
                ->required(),

            Forms\Components\Textarea::make('deskripsi_pekerjaan')
                ->label('Deskripsi Pekerjaan')
                ->rows(3)
                ->required(),

            Forms\Components\TextInput::make('jumlah_sdm')
                ->label('Jumlah SDM')
                ->numeric()
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

                Tables\Columns\TextColumn::make('volume')
                    ->label('Volume')
                    ->sortable(),


                Tables\Columns\TextColumn::make('deskripsi_pekerjaan')
                    ->label('Deskripsi Pekerjaan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('target_perminggu')
                    ->label('Target Perminggu')
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_perday')
                    ->label('Target Perhari')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('lokasi')
                    ->label('Filter Lokasi')
                    ->searchable()
                    ->options(fn () => TaskAplikasi::query()->distinct()->pluck('lokasi', 'lokasi')->toArray()),
                SelectFilter::make('pekerjaan')
                    ->label('Filter Pekerjaan')
                    ->searchable()
                    ->options(fn () => TaskAplikasi::query()->distinct()->pluck('pekerjaan', 'pekerjaan')->toArray()),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskAplikasis::route('/'),
            'create' => Pages\CreateTaskAplikasi::route('/create'),
            'edit' => Pages\EditTaskAplikasi::route('/{record}/edit'),
        ];
    }

    public static function calculateTargetPerminggu($get)
    {
        $volumeArsip = (float) $get('volume');
        $durasiProyek = (int) $get('durasi_proyek');

        if ($durasiProyek <= 0) {
            return 0;
        }

        return round($volumeArsip / $durasiProyek, 2);
    }

    public static function calculateTargetPerDay($get)
    {
        $volumeArsip = (float) $get('volume');
        $lamaPekerjaan = (int) $get('lama_pekerjaan');

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
    }

    /**
     * Override metode create untuk mengubah status marketing menjadi "on hold"
     */
    public static function create(array $data): TaskAplikasi
    {
        $taskaplikasi = TaskAplikasi::create($data);

        // Update status marketing menjadi "On Hold" dan log status
        Log::info('Updating marketing status to On Hold for marketing_id: ' . $data['marketing_id']);
        if (isset($data['marketing_id'])) {
            $marketing = Marketing::find($data['marketing_id']);
            if ($marketing) {
                $marketing->status = 'On Hold'; // Ubah status
                $marketing->save(); // Simpan perubahan
            }
        }

        return $taskaplikasi;
    }

    /**
     * Override metode update untuk mengubah status marketing menjadi "on hold"
     */
    public static function update(array $data, TaskAplikasi $record): TaskAplikasi
    {
        $record->update($data);

        // Update status marketing menjadi "On Hold" dan log status
        Log::info('Updating marketing status to On Hold for marketing_id: ' . $data['marketing_id']);
        if (isset($data['marketing_id'])) {
            $marketing = Marketing::find($data['marketing_id']);
            if ($marketing) {
                $marketing->status = 'On Hold'; // Ubah status
                $marketing->save(); // Simpan perubahan
            }
        }

        return $record;
    }
}
