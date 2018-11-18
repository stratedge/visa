<?php

namespace Stratedge\Visa\Test;

use Laravel\Passport\ClientRepository as BaseRepository;
use Stratedge\Visa\ClientRepository;

class RegisterOverloadsTest extends \Stratedge\Visa\Test\TestCase
{
    public function testOverridesClientRepository()
    {
        $clientRepository = $this->app->make(BaseRepository::class);
        $this->assertInstanceOf(ClientRepository::class, $clientRepository);
    }
}
