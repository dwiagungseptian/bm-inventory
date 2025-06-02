<?php

namespace App\Observers;

use App\Models\AssetAssignment;
use App\Models\ManageAsset;

class AssetAssignmentObserver
{
    public function created(AssetAssignment $assignment): void
    {
        $this->syncAsset($assignment->assets_id);
    }

    public function updated(AssetAssignment $assignment): void
    {
        $this->syncAsset($assignment->assets_id);
    }

    public function deleted(AssetAssignment $assignment): void
    {
        $this->syncAsset($assignment->assets_id);
    }

    private function syncAsset(int $assetId): void
    {
        $asset = ManageAsset::find($assetId);
        if (! $asset) return;

        // Hitung assignment aktif
        $dipakaiCount = AssetAssignment::where('assets_id', $assetId)
            ->where('status', 'Dipakai')
            ->count();

        $stok = max($asset->jumlah_barang - $dipakaiCount, 0);
        $asset->stok_barang = $stok;

        // Update status hanya jika bukan rusak/perbaikan
        if (! in_array($asset->status, ['Rusak', 'Dalam Perbaikan'])) {
            $asset->status = $stok > 0 ? 'Tersedia' : 'Dipakai';
        }

        $asset->save();
    }
}
