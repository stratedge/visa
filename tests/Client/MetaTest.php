<?php

namespace Stratedge\Visa\Test;

use Stratedge\Visa\Client;

class MetaTest extends \Stratedge\Visa\Test\TestCase
{
    public function testIsNotIncrementing()
    {
        $client = $this->app->make(Client::class);

        $this->assertFalse($client->incrementing);
    }
}
