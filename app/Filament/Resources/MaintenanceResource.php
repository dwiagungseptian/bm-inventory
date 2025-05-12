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

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    public static function getNavigationBadge(): ?string
    {
        if (auth()->check() && auth()->user()->hasAnyRole('Infrastruktur', 'super_admin')) {
            return static::getModel()::count();
        }

        return null;
    }
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Pengelolaan Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('asset_assignment_id')
                                    ->label('Aset yang Dipinjam')
                                    ->options(function () {
                                        return \App\Models\AssetAssignment::with(['manageAsset', 'user'])
                                            ->where('user_id', auth()->id())
                                            ->whereNull('returned_at')
                                            ->get()
                                            ->mapWithKeys(function ($assignment) {
                                                return [
                                                    $assignment->id => optional($assignment->manageAsset)->nama_aset . ' - ' . optional($assignment->user)->name,
                                                ];
                                            });
                                    })
                                    ->getOptionLabelUsing(function ($value): ?string {
                                        $assignment = \App\Models\AssetAssignment::with(['manageAsset', 'user'])->find($value);
                                        return $assignment
                                            ? optional($assignment->manageAsset)->nama_aset . ' - ' . optional($assignment->user)->name
                                            : null;
                                    })
                                    ->required()
                                    ->searchable(),
                                    Forms\Components\Textarea::make('keterangan')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                               
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Diajukan' => 'Diajukan',
                                        'Approve' => 'Disetujui',
                                        'Ditolak' => 'Ditolak',
                                    ])
                                    ->default('Diajukan')
                                    ->required()
                                    ->visible(fn() => auth()->user()->hasRole('Infrastruktur'))
                                    ->dehydrated(fn() => auth()->user()->hasRole('Infrastruktur'))
                                    ->live(),


                                Forms\Components\FileUpload::make('lampiran')
                                    ->required(),

                                Forms\Components\Textarea::make('alasan_ditolak')
                                    ->label('Alasan Ditolak')
                                    ->visible(fn(Forms\Get $get) => auth()->user()->hasRole('Infrastruktur') && $get('status') === 'Ditolak')
                                    ->requiredIf('status', 'Ditolak'),
                            ])
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = auth()->user()->hasAnyRole(['super_admin', 'Infrastruktur']);
                if (! $is_super_admin) {
                    $query->whereHas('assetAssignment', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('assetAssignment.user.name')
                    ->label('Pegawai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assetAssignment.manageAsset.nama_aset')
                    ->label('Nama Aset')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Diajukan' => 'warning',
                        'Approve' => 'success',
                        'Ditolak' => 'danger',
                    }),
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
