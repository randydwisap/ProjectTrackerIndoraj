<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisInstrumenResource\Pages;
use App\Filament\Resources\JenisInstrumenResource\RelationManagers;
use App\Models\JenisInstrumen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisInstrumenResource extends Resource
{
    protected static ?string $model = JenisInstrumen::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Lainnya';
    protected static ?string $pluralLabel = 'Master Tahap Instrumen';
    
    protected static ?string $navigationLabel = 'Master Tahap Instrumen';
    protected static ?int $navigationSort = 4; // Menentukan urutan menu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_task')->required()->label('Tahap Instrumen'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('nama_task')->sortable()->label('Tahap Instrumen'),
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
            'index' => Pages\ListJenisInstrumens::route('/'),
            'create' => Pages\CreateJenisInstrumen::route('/create'),
            'edit' => Pages\EditJenisInstrumen::route('/{record}/edit'),
        ];
    }
}
