<?php

namespace App\Observers;

use App\Models\AssetAssignment;
use App\Models\ManageAsset;

class ManageAssetObserver
{
    public function saving(ManageAsset $asset): void
    {
        // Hitung ulang stok hanya jika jumlah_barang berubah
        if ($asset->isDirty('jumlah_barang')) {
            $dipakaiCount = AssetAssignment::where('assets_id', $asset->id)
                ->where('status', 'Dipakai')
                ->count();

            $asset->stok_barang = max($asset->jumlah_barang - $dipakaiCount, 0);
        }

        // Jika status bukan Rusak / Dalam Perbaikan, tentukan otomatis
        if (!in_array($asset->status, ['Rusak', 'Dalam Perbaikan'])) {
            $asset->status = $asset->stok_barang > 0 ? 'Tersedia' : 'Dipakai';
        }
    }
    /**
     * Handle the ManageAsset "created" event.
     */
    public function created(ManageAsset $manageAsset): void
    {
        //
    }

    /**
     * Handle the ManageAsset "updated" event.
     */
    public function updated(ManageAsset $manageAsset): void
    {
        //
    }

    /**
     * Handle the ManageAsset "deleted" event.
     */
    public function deleted(ManageAsset $manageAsset): void
    {
        //
    }

    /**
     * Handle the ManageAsset "restored" event.
     */
    public function restored(ManageAsset $manageAsset): void
    {
        //
    }

    /**
     * Handle the ManageAsset "force deleted" event.
     */
    public function forceDeleted(ManageAsset $manageAsset): void
    {
        //
    }
}
