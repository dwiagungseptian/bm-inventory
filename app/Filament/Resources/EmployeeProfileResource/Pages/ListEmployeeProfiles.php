<?php

namespace App\Filament\Resources\EmployeeProfileResource\Pages;

use App\Filament\Resources\EmployeeProfileResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeProfiles extends ListRecords
{
    protected static string $resource = EmployeeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Excel')
                ->url(route('pegawai-export'))
                ->color('danger'),
            Actions\CreateAction::make(),
        ];
    }
}
