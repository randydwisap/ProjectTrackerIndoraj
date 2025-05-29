<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Manajemen Akun';
    protected static ?int $navigationSort = 5; // Menentukan urutan menu
    
    // ✅ Pastikan menu hanya bisa dilihat jika memiliki izin "role.view"
    public static function canViewAny(): bool
    {
    /** @var User $user */
    $user = auth()->user();

    return $user && $user->can('role.view');
    }
    public static function canCreate(): bool
    {
     /** @var User $user */
    $user = auth()->user();

    return $user && $user->can('role.create');
    }
    
    public static function canEdit($record): bool
    {
     /** @var User $user */
     $user = auth()->user();

    return $user && $user->can('role.update');
    }
    
    public static function canDelete($record): bool
    {
    /** @var User $user */
    $user = auth()->user();

    return $user && $user->can('role.delete');
    }
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Role')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Section::make('Permissions')
                ->schema([
                    CheckboxList::make('permissions')
                        ->label('Pilih Permissions')
                        ->relationship('permissions', 'name') // Hubungkan ke model Spatie
                        ->columns(3) // Atur jumlah kolom
                        ->searchable() // Tambahkan pencarian
                        ->bulkToggleable(), // Tambahkan opsi select all
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Role')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('permissions.name')
                ->label('Hak Akses')
                ->badge()
                ->toggleable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
