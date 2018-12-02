[![Build Status](https://travis-ci.org/stratedge/visa.svg?branch=master)](https://travis-ci.org/stratedge/visa)
[![Latest Stable Version](https://poser.pugx.org/stratedge/visa/v/stable)](https://packagist.org/packages/stratedge/visa)
[![Total Downloads](https://poser.pugx.org/stratedge/visa/downloads)](https://packagist.org/packages/stratedge/visa)
[![License](https://poser.pugx.org/stratedge/visa/license)](https://packagist.org/packages/stratedge/visa)

# Visa

A complimentary extension of the official Laravel Passport package.

Visa provides the following functionality:

* Use a random string for Client ID;
* Add configuration to use a UUID for Client ID instead of a random string;
* Provide the `CheckFirstPartyClient` middleware class to authenticate a client as a first party client;
* Add configuration to use the global Laravel error handler to handle errors thrown by Passport instead of Passport's built-in handler; and
* Provide overwrite-able `enableCustomGrants()` method in the service provider to make registering custom grants easier.

# Installation

Visa is registered with [Packagist](https://packagist.org) and can be installed with [Composer](https://getcomposer.org). Run the following on the command line:

```sh
composer require stratedge/visa
```

Since Visa _extends_ Passport, installing Visa will install Passport for you.

For versions of Laravel that support auto-registration of packages, Visa will automatically register itself. For older versions, be sure to add `Stratedge\Visa\VisaServiceProvider::class` to your list of service providers in `config/app.php`.

> No need to include Passport's service provider, the Visa provider extends it.

From here, complete all the typical [Passport installation steps](https://laravel.com/docs/master/passport#installation).

> **PLEASE NOTE:** See configuration below to ensure you complete any optional Visa configurations before running migrations to ensure columns are created with the correct types.

# Configuration

To configure core Passport features, refer to the [Passport documentation](https://laravel.com/docs/master/passport). Since Visa uses Passport, you're free to configure whatever you want from Passport.

## Using Random Strings for Client ID

By default Visa will use random 40-character strings for client IDs, the same as the client secrets. No configuration required.

## Using UUIDs for Client ID

Visa also supports UUIDs for client IDs, but must be configured to do so _before migrations are run_ so that the migrations specify the correct column type for `client_id`.

To use UUIDs, call `\Stratedge\Visa\Visa::enableClientUUIDs()` in the `boot()` method of your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stratedge\Visa\Visa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configure UUIDs for OAuth client IDs
        Visa::enableClientUUIDs();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

```

## Using the `CheckFirstPartyClient` Middleware

To use the `CheckFirstPartyClient` middleware, add the following middleware to the `$routeMiddleware` property of your `App\Http\Kernel` class:

```php
protected $routeMiddleware = [
    'auth.first-party' => \Stratedge\Visa\Http\Middleware\CheckFirstPartyClient::class,
];
```

The middleware can then be registered for any route or route group with the key `auth.first-party`. Any clients that are not considered first-party that attempt to authenticate with endpoints assigned the `CheckFirstPartyClient` middleware will fail with an authentication error.

## Error Handling

By default, Visa will use the built-in Passport error handler that will catch and respond to errors automatically. If you wish to disable the built-in handler and use the global Laravel error handler to control the logging and output of errors, call `\Stratedge\Visa\Visa::disablePassportErrorHandling()` in the `boot()` method of your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stratedge\Visa\Visa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Do not use the standard Passport error handling
        Visa::disablePassportErrorHandling();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

```

## Enabling Custom Grants

In order to use your own custom grants, you must extend the `Stratedge\Visa\VisaServiceProvider` class and overload the `enableCustomGrants()` method.

The method accepts a single parameter of type `League\OAuth2\Server\AuthorizationServer` that will be injected automatically as the argument `$server`. Use the `enableGrantType()` method of the `AuthorizationServer` class to enable new grant types.

```php
<?php

namespace App\Providers;

use Stratedge\Visa\VisaServiceProvider as BaseProvider;

class VisaServiceProvider extends BaseProvider
{
    /**
     * Enables any additional custom grants when overloaded.
     *
     * @param  AuthorizationServer $server
     * @return void
     */
    public function enableCustomGrants(AuthorizationServer $server)
    {
        $server->enableGrantType(/* Register custom grant here */);
    }
}
```

> **PLEASE NOTE:** When extending the service provider, be sure to register your custom `VisaServiceProvider` in the `app.php` configuration file and turn off the auto-discovery of the default provider provided by this library through your `composer.json` file.

# Extension Philosophy

Because Passport is an authorization library, integrating security patches quickly is immensely important. Unlike a fork, which creates a separately maintained version of the entire Passport library, Visa is a complimentary package that sits alongside the core Passport in your project. In that sense Visa extends Passport and makes only the smallest required changes.

In fact, when Visa is installed it will automatically require Passport. But since Passport remains a separate library, the developer is free to specify the version of Passport they wish to use. This allows the developer to take advantage of incremental security updates in Passport without requiring corresponding changes to Visa. So long as the newest version of Passport makes no breaking API changes, changing the Passport version should work just fine.
