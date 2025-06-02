<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetAssignmentResource\Pages;
use App\Models\AssetAssignment;
use App\Models\ManageAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AssetAssignmentResource extends Resource
{
    protected static ?string $model = AssetAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Pengelolaan Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required(),

                                Forms\Components\Select::make('assets_id')
                                    ->required()
                                    ->relationship('manageAsset', 'nama_aset')
                                    ->options(
                                        ManageAsset::where('stok_barang', '>', 0)
                                            ->pluck('nama_aset', 'id')
                                            ->toArray()
                                    ),

                                Forms\Components\DatePicker::make('assigned_at')
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Textarea::make('keterangan')
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Dipakai' => 'Dipakai',
                                        'Dikembalikan' => 'Dikembalikan',
                                    ])
                                    ->default('Dipakai')
                                    ->required(),

                                Forms\Components\DatePicker::make('returned_at'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (!Auth::user()->hasAnyRole('super_admin', 'Infrastruktur')) {
                    $query->where('user_id', auth()->id());
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pegawai'),

                Tables\Columns\TextColumn::make('manageAsset.nama_aset')
                    ->label('Nama Aset'),

                Tables\Columns\TextColumn::make('assigned_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('returned_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Dipakai' => 'success',
                        'Dikembalikan' => 'warning',
                    }),

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

    public static function afterCreate(Form $form, $record): void
    {
        if ($record->status === 'Dipakai') {
            $asset = ManageAsset::find($record->assets_id);
            if ($asset && $asset->stok_barang > 0) {
                $asset->decrement('stok_barang');
                $asset->save();
            }
        }
    }

    public static function getRelations(): array
    {
        return [];
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
