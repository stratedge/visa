<?php

namespace Stratedge\Visa\Test\VisaServiceProvider;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Stratedge\Visa\Client;
use Stratedge\Visa\VisaServiceProvider;

class BootTest extends \Stratedge\Visa\Test\TestCase
{
    public function testRegistersCustomClientModel()
    {
        $client = Passport::client();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testKeepsAlternativeClientModel()
    {
        $currentModel = Passport::$clientModel;

        Passport::useClientModel(Passport::class);

        $provider = new VisaServiceProvider(app());

        $provider->boot();

        $client = Passport::client();

        $this->assertInstanceOf(Passport::class, $client);

        // Reset configuration
        Passport::useClientModel($currentModel);
    }

    public function testPublishesVisaMigrations()
    {
        $publishes = ServiceProvider::$publishes;

        $this->assertInternalType('array', $publishes[VisaServiceProvider::class]);
        $this->assertArrayHasKey(VisaServiceProvider::class, $publishes);
        $this->assertNotEmpty($publishes[VisaServiceProvider::class]);

        $publishes = $publishes[VisaServiceProvider::class];

        $publishesMigrations = false;

        foreach ($publishes as $key => $value) {
            $keyPath = realpath($key);

            if ($keyPath) {
                if ($keyPath === realpath(__DIR__ . '/../../database/migrations')) {
                    $publishesMigrations = $value === database_path('migrations');
                }
            }
        }

        $this->assertTrue($publishesMigrations);
    }

    public function testShouldRegisterMigrationsByDefault()
    {
        $loadsMigrations = false;

        foreach ($this->app->migrator->paths() as $path) {
            $path = realpath($path);

            if ($path) {
                if ($path === realpath(__DIR__ . '/../../database/migrations')) {
                    $loadsMigrations = true;
                }
            }
        }

        $this->assertTrue($loadsMigrations);
    }

    public function testShouldNotRegisterMigrationsWhenConfigured()
    {
        Passport::ignoreMigrations();
        $this->refreshApplication();

        $loadsMigrations = false;

        foreach ($this->app->migrator->paths() as $path) {
            $path = realpath($path);

            if ($path) {
                if ($path === realpath(__DIR__ . '/../../database/migrations')) {
                    $loadsMigrations = true;
                }
            }
        }

        $this->assertFalse($loadsMigrations);

        // Reset configuration
        Passport::$runsMigrations = true;
        $this->refreshApplication();
    }
}
