<?php

namespace Stratedge\Visa\Test;

use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Laravel\Passport\Passport;
use Stratedge\Visa\Visa;
use Stratedge\Wye\Wye;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Specify the providers to be used in the test application.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Stratedge\Visa\VisaServiceProvider'];
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        Wye::reset();

        Connection::resolverFor('mysql', function () {
            return new MySqlConnection(Wye::makePDO());
        });

        parent::setUp();

        // Reset configuration
        Passport::$implicitGrantEnabled = false;
        Visa::$clientUUIDsEnabled = false;
        Visa::$passportErrorHandlingDisabled = false;
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('passport.public_key', realpath(__DIR__ . '/oauth-public.key'));
        $app['config']->set('passport.private_key', realpath(__DIR__ . '/oauth-private.key'));
    }
}
