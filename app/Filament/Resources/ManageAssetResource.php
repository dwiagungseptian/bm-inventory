<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManageAssetResource\Pages;
use App\Filament\Resources\ManageAssetResource\RelationManagers;
use App\Models\ManageAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageAssetResource extends Resource
{
    protected static ?string $model = ManageAsset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_aset')
                    ->required(),
                Forms\Components\TextInput::make('kode_aset')
                    ->required(),
                Forms\Components\TextArea::make('keterangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'Tersedia' => 'Tersedia',
                        'Rusak' => 'Rusak',
                        'Dalam Perbaikan' => 'Dalam Perbaikan',
                        'Dipakai' => 'Dipakai',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('gambar')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_pembelian')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Rusak' => 'dangers',
                        'Dalam Perbaikan' => 'warning',
                        'Dipakai' => 'info',
                    }),
                Tables\Columns\ImageColumn::make('gambar')
                     ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('tanggal_pembelian'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListManageAssets::route('/'),
            'create' => Pages\CreateManageAsset::route('/create'),
            'edit' => Pages\EditManageAsset::route('/{record}/edit'),
        ];
    }
}
