<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskFumigasiResource\Pages;
use App\Filament\Resources\TaskFumigasiResource\RelationManagers\ReportFumigasiRelationManagers;
use App\Models\TaskFumigasi;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Marketing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskFumigasiResource extends Resource
{
    protected static ?string $model = TaskFumigasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Proyek';
    protected static ?string $navigationGroup = 'Pengolahan Fumigasi';
    protected static ?string $pluralLabel = 'Proyek';
    protected static ?int $navigationSort = 1; // Menentukan urutan menu


    public static function getRelations(): array
    {
        return [
            ReportFumigasiRelationManagers::class,
       ];
    }
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
                ->live()
                ->extraAttributes(['id' => 'marketing_id']) // Tambahkan ID untuk JavaScript
                ->options(
                    Marketing::where('status', 'Persiapan Operasional')
                        ->where('jenis_pekerjaan', 'Fumigasi')
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
                        $set('nilai_proyek', $marketing->nilai_akhir_proyek);
                        $set('link_rab', $marketing->link_rab);
                        $set('volume', $marketing->total_volume);                        
                        $set('status', 'Behind Schedule');
                        $set('marketing_note_operasional', $marketing->note_operasional);
                        $set('tahap_pengerjaan', 'Persiapan dan Pemberian Fumigan');                        

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
                ->options(\App\Models\JenisTahapFumigasi::pluck('nama_task', 'nama_task'))
                ->default('Persiapan dan Pemberian Fumigan') // Sesuaikan default yang valid
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
                ->live()
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
                
            Forms\Components\Textarea::make('alamat')
                ->label('Alamat')
                ->rows(3)
                ->required(),

            Forms\Components\TextInput::make('volume')
                ->label('Volume')
                ->prefix('Satuan')
                ->numeric()
                ->inputMode('decimal')
                ->step(0.01) 
                ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', ''))
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
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )    
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi_pekerjaan')
                    ->label('Deskripsi Pekerjaan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('target_perminggu')
                    ->label('Target Perminggu')
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )    
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_perday')
                    ->label('Target Perhari')
                    ->numeric(
                            decimalPlaces: 1, // Menampilkan 3 digit desimal
                            decimalSeparator: '.',
                            thousandsSeparator: ','
                        )    
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('lokasi')
                    ->label('Filter Lokasi')
                    ->searchable()
                    ->options(fn () => TaskFumigasi::query()->distinct()->pluck('lokasi', 'lokasi')->toArray()),
                SelectFilter::make('pekerjaan')
                    ->label('Filter Pekerjaan')
                    ->searchable()
                    ->options(fn () => TaskFumigasi::query()->distinct()->pluck('pekerjaan', 'pekerjaan')->toArray()),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Print')
                    ->url(fn ($record) => url('/task-fumigasi/' . $record->id . '/print'))
                    ->icon('heroicon-o-printer')
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->no_st) && !empty($record->tgl_surat)),
                Tables\Actions\Action::make('Buat ST')
                    ->visible(fn ($record) => 
                        is_null($record->no_st) &&
                        is_null($record->tgl_surat) &&
                        (
                            auth()->user()?->hasRole('Manajer Operasional') ||
                            auth()->user()?->hasRole('Manajer Keuangan')
                        )
                    )
                    ->label('Buat Surat Tugas')
                    ->icon('heroicon-o-document-plus')
                    ->form([
                        Forms\Components\Fieldset::make('Nomor ST')
                            ->columns(1)
                            ->schema([
                                Forms\Components\Grid::make(5)
                                    ->schema([
                                        Forms\Components\TextInput::make('prefix_display')
                                            ->default('DP.00.01')
                                            ->disabled()
                                            ->label(false),

                                        Forms\Components\Hidden::make('prefix')
                                            ->default('DP.00.01'),

                                        Forms\Components\TextInput::make('kode')
                                            ->helperText('Hanya isi bagian angka ini, misalnya: 092')
                                            ->maxLength(3)
                                            ->label(false),

                                        Forms\Components\TextInput::make('unit_display')
                                            ->default('IAM')
                                            ->disabled()
                                            ->label(false),

                                        Forms\Components\Hidden::make('unit')
                                            ->default('IAM'),

                                        Forms\Components\TextInput::make('bulan_romawi_display')
                                            ->default(fn ($livewire) => toRomawi(\Carbon\Carbon::parse($livewire->record->tgl_surat ?? now())->format('m')))
                                            ->disabled()
                                            ->reactive()
                                            ->label(false),

                                        Forms\Components\Hidden::make('bulan_romawi')
                                            ->default(fn ($livewire) => toRomawi(\Carbon\Carbon::parse($livewire->record->tgl_surat ?? now())->format('m')))
                                            ->reactive(),

                                        Forms\Components\TextInput::make('tahun_display')
                                            ->default(fn ($livewire) => \Carbon\Carbon::parse($livewire->record->tgl_surat ?? now())->format('Y'))
                                            ->disabled()
                                            ->reactive()
                                            ->label(false),

                                        Forms\Components\Hidden::make('tahun')
                                            ->default(fn ($livewire) => \Carbon\Carbon::parse($livewire->record->tgl_surat ?? now())->format('Y'))
                                            ->reactive(),
                                    ]),
                            ]),
                        Forms\Components\DatePicker::make('tgl_surat')
                            ->label('Tanggal Surat')
                            ->required()
                            ->default(now())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('bulan_romawi', toRomawi(\Carbon\Carbon::parse($state)->format('m')));
                                $set('tahun', \Carbon\Carbon::parse($state)->format('Y'));
                            }),
                    ])
                    ->action(function ($record, array $data) {
                        $no_st = "{$data['prefix']}/{$data['kode']}/{$data['unit']}/{$data['bulan_romawi']}/{$data['tahun']}";
                        $record->update([
                            'no_st' => $no_st,
                            'tgl_surat' => $data['tgl_surat'],
                        ]);
                    })
                    ->modalHeading('Buat Surat Tugas')
                    ->modalSubmitActionLabel('Simpan'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskFumigasis::route('/'),
            'create' => Pages\CreateTaskFumigasi::route('/create'),
            'edit' => Pages\EditTaskFumigasi::route('/{record}/edit'),
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

    public static function calculateTargetPerminggu($get)
    {
        $jumlahTahap = \App\Models\JenisTahapFumigasi::count();
        $volumeArsip = (float) $get('volume');
        $durasiProyek = (int) $get('durasi_proyek');

        if ($durasiProyek <= 0) {
            return 0;
        }

        return round($volumeArsip * $jumlahTahap / $durasiProyek, 2);
    }

    public static function calculateTargetPerDay($get)
    {
        $jumlahTahap = \App\Models\JenisTahapFumigasi::count();
        $volumeArsip = (float) $get('volume');
        $lamaPekerjaan = (int) $get('lama_pekerjaan');

        if ($lamaPekerjaan <= 0) {
            return 0;
        }

        return round($volumeArsip * $jumlahTahap / $lamaPekerjaan, 2);
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
    public static function create(array $data): TaskFumigasi
    {
        $taskfumigasi = TaskFumigasi::create($data);

        // Update status marketing menjadi "On Hold" dan log status
        Log::info('Updating marketing status to On Hold for marketing_id: ' . $data['marketing_id']);
        if (isset($data['marketing_id'])) {
            $marketing = Marketing::find($data['marketing_id']);
            if ($marketing) {
                $marketing->status = 'Pengerjaan'; // Ubah status
                $marketing->save(); // Simpan perubahan
            }
        }

        return $taskfumigasi;
    }

    /**
     * Override metode update untuk mengubah status marketing menjadi "on hold"
     */
    public static function update(array $data, TaskFumigasi $record): TaskFumigasi
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
}
