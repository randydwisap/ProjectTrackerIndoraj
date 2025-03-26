<?php

namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Marketing;
use App\Filament\Resources\TaskResource\RelationManagers\TaskWeekOverviewRelationManager;
use App\Filament\Resources\TaskResource\RelationManagers\TaskDetailRelationManager;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Tugas';
    protected static ?string $navigationGroup = 'Manajemen Tugas';
    protected static ?string $pluralLabel = 'Tasks';
    protected static ?int $navigationSort = 1; // Menentukan urutan menu

    public static function getRelations(): array
    {
        return [
            TaskWeekOverviewRelationManager::class,
            TaskDetailRelationManager::class,
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
                ->options(Marketing::where('status', 'Completed')->pluck('nama_pekerjaan', 'id'))
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $marketing = Marketing::find($state);
                    $user = User::find($state);
                    if ($marketing) {
                        $set('pekerjaan', $marketing->nama_pekerjaan);
                        $set('klien', $marketing->nama_klien);
                        $set('lokasi', $marketing->lokasi);
                        $set('tahap_pengerjaan', $marketing->tahap_pengerjaan);
                        $set('tgl_mulai', $marketing->tgl_mulai);
                        $set('tgl_selesai', $marketing->tgl_selesai);
                        $set('nilai_proyek', $marketing->nilai_akhir_proyek);
                        $set('link_rab', $marketing->link_rab);
                        $set('jenis_arsip', $marketing->jenis_pekerjaan);
                        $set('volume_arsip', $marketing->total_volume);
                        $set('no_telp_pm', $user->telepon);
                        $set('status', 'Behind Schedule');
                        $set('tahap_pengerjaan', 'Pemilahan');
                        $set('project_manager', auth()->id());

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
                ->options(\App\Models\JenisTask::pluck('nama_task', 'nama_task'))
                ->default(1),

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
                    'Moderate' => 'Moderate',
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
                ->label('Project Manager')
                ->options(User::pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('no_telp_pm')
                ->label('No Telp. PM')
                ->tel()
                ->required(),


            Forms\Components\TextInput::make('link_rab')
                ->label('Link RAB')
                ->url()
                ->nullable(),

            Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi')
                ->required(),

            Forms\Components\TextInput::make('volume_arsip')
                ->label('Volume Arsip (mL)')
                ->prefix('mL ')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('hasil_pemilahan')
                ->label('Volume Arsip Pemilahan(mL)')
                ->prefix('mL ')
                ->hidden()
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('jenis_arsip')
                ->label('Jenis Arsip')
                ->required(),

            Forms\Components\TextInput::make('target_perminggu')
                ->label('Target Perminggu (mL)')
                ->numeric()
                ->disabled()
                ->default(fn ($get) => static::calculateTargetPerminggu($get))
                ->dehydrateStateUsing(fn ($state, $get) => static::calculateTargetPerminggu($get))
                ->dehydrated()
                ->required(),

            Forms\Components\TextInput::make('target_perday')
                ->label('Target Perhari (mL)')
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
                        'Moderate' => 'warning',
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

                Tables\Columns\TextColumn::make('volume_arsip')
                    ->label('Volume Arsip (mL)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hasil_pemilahan')
                    ->label('Volume Arsip Pemilahan (mL)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_arsip')
                    ->label('Jenis Arsip')
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi_pekerjaan')
                    ->label('Deskripsi Pekerjaan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('target_perminggu')
                    ->label('Target Perminggu (mL)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_perday')
                    ->label('Target Perhari (mL)')
                    ->sortable(),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function calculateTargetPerminggu($get)
    {
        $volumeArsip = (float) $get('volume_arsip');
        $durasiProyek = (int) $get('durasi_proyek');

        if ($durasiProyek <= 0) {
            return 0;
        }

        return round($volumeArsip / $durasiProyek, 2);
    }

    public static function calculateTargetPerDay($get)
    {
        $volumeArsip = (float) $get('volume_arsip');
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
    public static function create(array $data): Task
    {
        $task = Task::create($data);

        // Update status marketing menjadi "On Hold" dan log status
        Log::info('Updating marketing status to On Hold for marketing_id: ' . $data['marketing_id']);
        if (isset($data['marketing_id'])) {
            $marketing = Marketing::find($data['marketing_id']);
            if ($marketing) {
                $marketing->status = 'On Hold'; // Ubah status
                $marketing->save(); // Simpan perubahan
            }
        }

        return $task;
    }

    /**
     * Override metode update untuk mengubah status marketing menjadi "on hold"
     */
    public static function update(array $data, Task $record): Task
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
