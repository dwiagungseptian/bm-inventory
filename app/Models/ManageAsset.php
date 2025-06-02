<?php

namespace App\Models;

use Filament\Support\Assets\Asset;
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
        'stok_barang',
        'jumlah_barang',
        'tanggal_pembelian',
    ];

    public function assetAssignments()
{
    return $this->hasMany(AssetAssignment::class, 'assets_id');
}
}
