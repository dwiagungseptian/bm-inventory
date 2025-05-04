<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable =[
        'asset_assignment_id',
        'keterangan',
        'status',
        'lampiran',
        'alasan_ditolak',
    ];

    public function assetAssignment()
{
    return $this->belongsTo(AssetAssignment::class);
}
}
