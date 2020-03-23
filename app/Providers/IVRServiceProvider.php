<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IVRServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\IUserService', 'App\Services\UserService');
        $this->app->bind('App\Services\ICrmService', 'App\Services\CrmService');
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
