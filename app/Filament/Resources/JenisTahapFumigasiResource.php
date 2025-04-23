<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisTahapFumigasiResource\Pages;
use App\Filament\Resources\JenisTahapFumigasiResource\RelationManagers;
use App\Models\JenisTahapFumigasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisTahapFumigasiResource extends Resource
{
    protected static ?string $model = JenisTahapFumigasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Pengolahan Fumigasi';
    protected static ?string $pluralLabel = 'Master Tahapan Proyek Fumigasi';
     // Updated navigation label
    protected static ?string $navigationLabel = 'Master Tahapan Proyek';
    protected static ?int $navigationSort = 3; // Menentukan urutan menu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_task')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_task')->sortable(),
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
            'index' => Pages\ListJenisTahapFumigasis::route('/'),
            'create' => Pages\CreateJenisTahapFumigasi::route('/create'),
            'edit' => Pages\EditJenisTahapFumigasi::route('/{record}/edit'),
        ];
    }
}
