<?php

namespace Stratedge\Visa;

use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BaseServiceProvider;

class VisaServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Use the Visa Client model that expects the ID to be a
        // non-incrementing string
        Passport::useClientModel(Client::class);

        $this->loadViewsFrom(basepath('vendor/laravel/passport/resources/views'), 'passport');

        $this->deleteCookieOnLogout();

        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'passport-migrations');

            $this->publishes([
                basepath('vendor/laravel/passport/resources/views') => base_path('resources/views/vendor/passport'),
            ], 'passport-views');

            $this->publishes([
                basepath('vendor/laravel/passport/resources/js/components') => base_path('resources/js/components/passport'),
            ], 'passport-components');

            $this->commands([
                \Laravel\Passport\Console\InstallCommand::class,
                \Laravel\Passport\Console\ClientCommand::class,
                \Laravel\Passport\Console\KeysCommand::class,
            ]);
        }
    }

    /**
     * Register Visa's migration files in place of Passport's.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Passport::$runsMigrations) {
            return $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerOverloads();

        $this->registerAuthorizationServer();

        $this->registerResourceServer();

        $this->registerGuard();

        $this->offerPublishing();
    }

    /**
     * Register overloaded classes.
     *
     * @return void
     */
    public function registerOverloads()
    {
        $this->app->bind(
            \Laravel\Passport\ClientRepository::class,
            \Stratedge\Visa\ClientRepository::class
        );
    }
}
