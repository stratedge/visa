<?php

namespace Stratedge\Visa\Test;

use Stratedge\Visa\Visa;
use Stratedge\Visa\Client;
use Stratedge\Visa\ClientRepository;

class CreateTest extends \Stratedge\Visa\Test\TestCase
{
    public function testReturnsClient()
    {
        $clientRepository = $this->app->make(ClientRepository::class);

        $client = $clientRepository->create(123, 'testing', 'http://test.com');

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testSetsClientIDToRandomStringByDefault()
    {
        $clientRepository = $this->app->make(ClientRepository::class);

        $client = $clientRepository->create(123, 'testing', 'http://test.com');

        $this->assertRegExp('/^[A-z0-9]{40}$/', $client->id);
    }

    public function testSetsClientIDToUUIDWhenConfigured()
    {
        Visa::enableClientUUIDs();

        $clientRepository = $this->app->make(ClientRepository::class);

        $client = $clientRepository->create(123, 'testing', 'http://test.com');

        $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $client->id);

        // Reset configuration
        Visa::$clientUUIDsEnabled = false;
    }
}
