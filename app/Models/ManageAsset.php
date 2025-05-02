<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageAsset extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_aset',
        'kode_aset',
        'keterangan',
        'status',
        'gambar',
        'tanggal_pembelian',
    ];
}
