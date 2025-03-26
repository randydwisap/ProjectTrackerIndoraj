<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Manajemen Akun';

    protected static ?int $navigationSort = 1; // Menentukan urutan menu
    protected static ?string $navigationIcon = 'heroicon-o-users';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->label('NIP')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->label('NIK')
                    ->maxLength(255),
                Forms\Components\TextInput::make('telepon')
                    ->required()
                    ->label('NIP')
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
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->required()
                    ->label('Hak Akses'),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                ->password()
                ->maxLength(255)
                ->dehydrated(fn ($state) => !empty($state))
                ->required(fn ($record) => $record === null) // Hanya wajib saat tambah user
                //->visible(fn ($record) => $record === null || auth()->user()->can('user.update')) // Tampilkan jika bisa edit
                ->dehydrated(fn ($state) => filled($state)), // Hanya ubah jika diisi        
            ]);
    }
    public static function canViewAny(): bool
    {
    /** @var User|null $user */
    $user = Auth::user();
    return $user?->can('user.view') ?? false;
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')        
                    ->searchable()
                    ->label('NIK'),
                Tables\Columns\TextColumn::make('nip')        
                    ->searchable()
                    ->label('NIP'),
                Tables\Columns\TextColumn::make('Telepon')        
                    ->searchable()
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(fn ($record) => redirect(UserResource::getUrl('index'))), // Redirect setelah edit
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            //
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
}
