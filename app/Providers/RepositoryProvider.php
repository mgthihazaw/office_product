<?php

namespace App\Providers;

use App\Contracts\KeyContract;
use App\Repositories\KeyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(KeyContract::class, function () {
            return new KeyRepository();
        });
    }
}