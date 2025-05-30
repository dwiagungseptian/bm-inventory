<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use App\Models\ManageAsset;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $countUser = User::role(['Karyawan', 'Infrastruktur'])->count();
        $countAsset = ManageAsset::count();
        $countMaintenance = Maintenance::count();
        return [
            // Stat::make('Jumlah Pegawai', $countUser . ' Pegawai')
            // ->icon('heroicon-o-user-group')
            // ->description('')
            // ->descriptionIcon('heroicon-m-arrow-trending-up')
            // ->color('success'),
            // Stat::make('Jumlah Aset', $countAsset. ' Aset')
            // ->icon('heroicon-o-archive-box')
            // ->color('success'),
            // Stat::make('Asset dalam Perbaikan/Diperbaiki', $countMaintenance . ' Aset')
            //  ->icon('heroicon-o-cog')
            //  ->color('warning'),
            Stat::make('Jumlah Pegawai', $countUser . ' Pegawai')
                ->icon('heroicon-o-user-group')
                ->description('+' . $countUser . ' Pegawai Brainmatics bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('success')
                ->color('success'),

            Stat::make('Jumlah Aset', $countAsset . ' Aset')
                ->icon('heroicon-o-archive-box')
                ->description('+' . $countAsset . ' Aset Terekap')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('info'),


            Stat::make('Asset dalam Perbaikan/Diperbaiki', $countMaintenance . ' Aset')
                ->icon('heroicon-o-cog')
                ->description('+' . $countMaintenance . ' aset perbaikan/diperbaiki')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('warning')
                ->color('warning'),
        ];
    }
}
