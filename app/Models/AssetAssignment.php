<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'assets_id',
        'assigned_at',
        'returned_at',
        'keterangan',
        'status',
    ];

    public function manageAsset()
    {
        return $this->belongsTo(ManageAsset::class, 'assets_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
