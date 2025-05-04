<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Filament\Resources\MaintenanceResource\RelationManagers;
use App\Models\Maintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Nama Pengguna')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\Select::make('assets_id')
                    ->label('Nama Aset')
                    ->relationship('manage_asset', 'nama_aset')
                    ->required(),

                Forms\Components\Textarea::make('keterangan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options([
                        'Diajukan' => 'Diajukan',
                        'Approve' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->required()
                    ->live(), // Penting agar perubahan langsung terdeteksi

                Forms\Components\FileUpload::make('lampiran')
                    ->required(),

                Forms\Components\Textarea::make('alasan_ditolak')
                    ->label('Alasan Penolakan')
                    ->visible(fn(Forms\Get $get) => $get('status') === 'Ditolak')
                    ->requiredIf('status', 'Ditolak')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(function (Builder $query) {
            //     $is_super_admin = Auth()->user()->hasRole('super_admin');
            //     if (!$is_super_admin) {
            //         $query->where('user_id', auth()->user()->id);
            //     }
            // })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('manage_assets.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\ImageColumn::make('lampiran')
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
