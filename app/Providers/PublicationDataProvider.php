<?php

namespace App\Providers;

use App\Services\PublicationDataQuery\Contracts\DataProvider;
use App\Services\PublicationDataQuery\Providers\CrosRef;
use Illuminate\Support\ServiceProvider;

class PublicationDataProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DataProvider::class, function ($app) {
            return new CrosRef();
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
