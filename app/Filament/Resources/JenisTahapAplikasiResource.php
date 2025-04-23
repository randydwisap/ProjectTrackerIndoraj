<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisTahapAplikasiResource\Pages;
use App\Filament\Resources\JenisTahapAplikasiResource\RelationManagers;
use App\Models\JenisTahapAplikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisTahapAplikasiResource extends Resource
{
    protected static ?string $model = JenisTahapAplikasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Proyek Aplikasi';
    protected static ?string $pluralLabel = 'Master Tahapan Proyek Aplikasi';
        // Updated navigation label
        protected static ?string $navigationLabel = 'Master Tahapan Proyek';
        protected static ?int $navigationSort = 3; // Menentukan urutan menu
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListJenisTahapAplikasis::route('/'),
            'create' => Pages\CreateJenisTahapAplikasi::route('/create'),
            'edit' => Pages\EditJenisTahapAplikasi::route('/{record}/edit'),
        ];
    }
}
