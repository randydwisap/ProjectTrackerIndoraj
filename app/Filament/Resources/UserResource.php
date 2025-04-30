<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Manajemen Akun';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('NIP')
                            ->required()
                            ->label('NIP')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('NIK')
                            ->required()
                            ->label('NIK')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('Telepon')
                            ->required()
                            ->label('Nomor Telepon')
                            ->maxLength(255),

                        Forms\Components\Select::make('Jabatan')
                            ->required()
                            ->label('Jabatan')
                            ->options([
                                'Project Manager' => 'Project Manager',
                                'Marketing' => 'Marketing',
                                'Manajer Operasional' => 'Manajer Operasional',
                                'Manajer Keuangan' => 'Manajer Keuangan',
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Keamanan')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->label('Hak Akses')
                            ->options(Role::all()->pluck('name', 'id')),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Terverifikasi Pada'),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->confirmed()
                            ->hiddenOn('edit')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->maxLength(255)
                            ->requiredWith('password')
                            ->hiddenOn('edit'),

                        Forms\Components\TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->maxLength(255)
                            ->confirmed()
                            ->dehydrated(false)
                            ->hidden(fn (string $operation): bool => $operation !== 'edit'),

                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->maxLength(255)
                            ->hidden(fn (string $operation): bool => $operation !== 'edit'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('NIP')
                    ->label('NIP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('Jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter Berdasarkan Role'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Tambahkan relations jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    public static function mutateFormDataBeforeUpdate(array $data, array $arguments): array
    {
        if (isset($data['new_password']) && $data['new_password']) {
            $data['password'] = Hash::make($data['new_password']);
        }
        
        unset($data['new_password']);
        unset($data['new_password_confirmation']);
        unset($data['password_confirmation']);

        return $data;
    }
}