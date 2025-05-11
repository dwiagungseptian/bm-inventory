<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeProfileResource\Pages;
use App\Filament\Resources\EmployeeProfileResource\RelationManagers;
use App\Models\EmployeeProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeProfileResource extends Resource
{
    protected static ?string $model = EmployeeProfile::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function getNavigationLabel(): string
    {
        return 'Data Pegawai';
    }
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Pengelolaan Pegawai';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Nama Pegawai')
                    ->required(),
                Forms\Components\Select::make('position_id')
                    ->relationship('position', 'nama_jabatan')
                    ->label('Jabatan')
                    ->required(),
                Forms\Components\Select::make('division_id')
                    ->relationship('division', 'nama_divisi')
                    ->label('Divisi')
                    ->required(),
                Forms\Components\TextInput::make('no_hp')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = Auth()->user()->hasAnyRole('super_admin', 'Infrastruktur');
                if (!$is_super_admin) {
                    $query->where('user_id', auth()->user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.nama_jabatan')
                    ->label('Jabatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.nama_divisi')
                    ->label('Divisi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),
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
            'index' => Pages\ListEmployeeProfiles::route('/'),
            'create' => Pages\CreateEmployeeProfile::route('/create'),
            'edit' => Pages\EditEmployeeProfile::route('/{record}/edit'),
        ];
    }
}
