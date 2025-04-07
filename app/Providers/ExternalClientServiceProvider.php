<?php
namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Extensions\CustomTokenGuard;

class ExternalClientServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::extend('custom-token', function ($app, $name, array $config) {
            return new CustomTokenGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
    }

    public function register()
    {
        //
    }
}
