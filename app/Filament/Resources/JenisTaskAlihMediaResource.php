<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisTaskAlihMediaResource\Pages;
use App\Filament\Resources\JenisTaskAlihMediaResource\RelationManagers;
use App\Models\JenisTaskAlihMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisTaskAlihMediaResource extends Resource
{
    protected static ?string $model = JenisTaskAlihMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Alih Media';
    protected static ?string $pluralLabel = 'Master Tahapan Proyek';
    
    protected static ?string $navigationLabel = 'Master Tahapan Proyek';
    protected static ?int $navigationSort = 4; // Menentukan urutan menu

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
            'index' => Pages\ListJenisTaskAlihMedia::route('/'),
            'create' => Pages\CreateJenisTaskAlihMedia::route('/create'),
            'edit' => Pages\EditJenisTaskAlihMedia::route('/{record}/edit'),
        ];
    }
}
