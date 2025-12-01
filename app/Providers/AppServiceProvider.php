<?php

namespace App\Providers;

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
        mb_internal_encoding('UTF-8');

        // Force HTTPS en production
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // Fix pour les URLs mixtes
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
