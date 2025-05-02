<?php

namespace App\Filament\Resources\ManageAssetResource\Pages;

use App\Filament\Resources\ManageAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManageAsset extends EditRecord
{
    protected static string $resource = ManageAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
