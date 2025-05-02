<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'asset_id',
        'keterangan',
        'status',
        'lampiran',
        'alasan_ditolak',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manage_asset()
    {
        return $this->belongsTo(ManageAsset::class);
    }
}
