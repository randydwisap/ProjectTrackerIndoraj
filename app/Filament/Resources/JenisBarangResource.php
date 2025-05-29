<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisBarangResource\Pages;
use App\Filament\Resources\JenisBarangResource\RelationManagers;
use App\Models\JenisBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisBarangResource extends Resource
{
    protected static ?string $model = JenisBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Lainnya';
    protected static ?string $pluralLabel = 'Master Jenis Barang';
    
    protected static ?string $navigationLabel = 'Master Jenis Barang';
    protected static ?int $navigationSort = 4; // Menentukan urutan menu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_task')->required()->label('Jenis Barang'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_task')->sortable()->label('Jenis Barang'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListJenisBarangs::route('/'),
            'create' => Pages\CreateJenisBarang::route('/create'),
            'edit' => Pages\EditJenisBarang::route('/{record}/edit'),
        ];
    }
}
