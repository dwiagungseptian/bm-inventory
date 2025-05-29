<?php

namespace App\Filament\Resources\EmployeeProfileResource\Pages;

use App\Filament\Resources\EmployeeProfileResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListEmployeeProfiles extends ListRecords
{
    protected static string $resource = EmployeeProfileResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make()
            ->label('Tambah Pegawai'),
        ];
    
        if (Auth::user()->hasAnyRole('Infrastruktur', 'super_admin')) {
            $actions[] = Action::make('Export Excel')
                ->url(route('pegawai-export'))
                ->color('danger');
        }
    
        return $actions;
    }
}
