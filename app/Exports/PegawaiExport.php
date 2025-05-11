<?php

namespace App\Exports;

use App\Models\EmployeeProfile;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PegawaiExport implements  FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return EmployeeProfile::query()
        ->join('users', 'users.id', '=', 'employee_profiles.user_id')
        ->join('positions', 'positions.id', '=', 'employee_profiles.position_id')
        ->join('divisions', 'divisions.id', '=', 'employee_profiles.division_id')
        ->select([
            'users.name',
            'positions.nama_jabatan as jabatan',
            'divisions.nama_divisi as divisi',
            'employee_profiles.no_hp',
            'employee_profiles.alamat',
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Jabatan',
            'Divisi',
            'No HP',
            'Alamat',
        ];
    }
}
