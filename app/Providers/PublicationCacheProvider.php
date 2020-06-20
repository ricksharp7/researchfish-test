<?php

namespace App\Providers;

use App\Services\PublicationCacheService;
use Illuminate\Support\ServiceProvider;

class PublicationCacheProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('publication-cache', function () {
            return new PublicationCacheService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
