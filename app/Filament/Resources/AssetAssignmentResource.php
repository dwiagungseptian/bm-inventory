<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetAssignmentResource\Pages;
use App\Filament\Resources\AssetAssignmentResource\RelationManagers;
use App\Models\AssetAssignment;
use App\Models\ManageAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AssetAssignmentResource extends Resource
{
    protected static ?string $model = AssetAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('assets_id')
                    ->required()
                    ->relationship('manage_asset', 'nama_aset')
                    ->options(
                        ManageAsset::whereNotIn('status', ['Dipakai', 'Rusak', 'Maintenance'])
                            ->pluck('nama_aset', 'id')
                            ->toArray()
                    ),
                Forms\Components\DatePicker::make('assigned_at')
                    ->required(),
                Forms\Components\DatePicker::make('returned_at'),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Dipakai' => 'Dipakai',
                        'Dikembalikan' => 'Dikembalikan',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = Auth()->user()->hasRole('super_admin');
                if (!$is_super_admin) {
                    $query->where('user_id', auth()->user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pegawai'),
                Tables\Columns\TextColumn::make('manage_asset.nama_aset')
                    ->label('Nama Aset'),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('returned_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListAssetAssignments::route('/'),
            'create' => Pages\CreateAssetAssignment::route('/create'),
            'edit' => Pages\EditAssetAssignment::route('/{record}/edit'),
        ];
    }
}
