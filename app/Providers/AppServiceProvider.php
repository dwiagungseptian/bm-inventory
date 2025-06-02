<?php

namespace App\Providers;

use App\Models\AssetAssignment;
use App\Models\ManageAsset;
use App\Observers\AssetAssignmentObserver;
use App\Observers\ManageAssetObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (config('app.env') == 'local') {
        //     URL::forceScheme('https');
        // }
        AssetAssignment::observe(AssetAssignmentObserver::class);
         ManageAsset::observe(ManageAssetObserver::class);
    }
}
