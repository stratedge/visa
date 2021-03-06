<?php

namespace Stratedge\Visa;

use DateInterval;
use Laravel\Passport\Bridge\PersonalAccessGrant;
use Laravel\Passport\Client as BaseClient;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BaseServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;

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
        // non-incrementing string if the default Client model is still
        // registered
        if (Passport::$clientModel === BaseClient::class) {
            Passport::useClientModel(Client::class);
        }

        $this->loadViewsFrom(base_path('vendor/laravel/passport/resources/views'), 'passport');

        $this->deleteCookieOnLogout();

        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'passport-migrations');

            $this->publishes([
                base_path('vendor/laravel/passport/resources/views') => base_path('resources/views/vendor/passport'),
            ], 'passport-views');

            $this->publishes([
                base_path('vendor/laravel/passport/resources/js/components') => base_path('resources/js/components/passport'),
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

        $this->app->bind(
            \Laravel\Passport\Http\Controllers\AccessTokenController::class,
            \Stratedge\Visa\Http\Controllers\AccessTokenController::class
        );

        $this->app->bind(
            \Laravel\Passport\Http\Controllers\ApproveAuthorizationController::class,
            \Stratedge\Visa\Http\Controllers\ApproveAuthorizationController::class
        );

        $this->app->bind(
            \Laravel\Passport\Http\Controllers\AuthorizationController::class,
            \Stratedge\Visa\Http\Controllers\AuthorizationController::class
        );
    }

        /**
     * Register the authorization server.
     *
     * @return void
     */
    protected function registerAuthorizationServer()
    {
        $this->app->singleton(AuthorizationServer::class, function () {
            return tap($this->makeAuthorizationServer(), function ($server) {
                $server->enableGrantType(
                    $this->makeAuthCodeGrant(),
                    Passport::tokensExpireIn()
                );

                $server->enableGrantType(
                    $this->makeRefreshTokenGrant(),
                    Passport::tokensExpireIn()
                );

                $server->enableGrantType(
                    $this->makePasswordGrant(),
                    Passport::tokensExpireIn()
                );

                $server->enableGrantType(
                    new PersonalAccessGrant,
                    new DateInterval('P1Y')
                );

                $server->enableGrantType(
                    new ClientCredentialsGrant,
                    Passport::tokensExpireIn()
                );

                if (Passport::$implicitGrantEnabled) {
                    $server->enableGrantType(
                        $this->makeImplicitGrant(),
                        Passport::tokensExpireIn()
                    );
                }

                //Add additional grants through easily overloaded method
                $this->enableCustomGrants($server);
            });
        });
    }

    /**
     * Enables any additional custom grants when overloaded.
     *
     * @param  AuthorizationServer $server
     * @return void
     */
    public function enableCustomGrants(AuthorizationServer $server)
    {
        //
    }
}
