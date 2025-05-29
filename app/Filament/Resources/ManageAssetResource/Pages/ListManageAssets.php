<?php

namespace App\Filament\Resources\ManageAssetResource\Pages;

use App\Filament\Resources\ManageAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManageAssets extends ListRecords
{
    protected static string $resource = ManageAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Aset'),
        ];
    }
}
