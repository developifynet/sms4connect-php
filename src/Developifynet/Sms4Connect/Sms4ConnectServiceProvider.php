<?php

namespace Developifynet\Sms4Connect;

use Illuminate\Support\ServiceProvider;

class Sms4ConnectServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sms4connect', function () {
            return $this->app->make('Developifynet\Sms4Connect\Sms4ConnectSMS');
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

}
