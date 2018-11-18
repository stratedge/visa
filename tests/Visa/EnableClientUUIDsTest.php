<?php

namespace Stratedge\Visa\Test;

use Stratedge\Visa\Visa;

class EnableClientUUIDsTest extends \Stratedge\Visa\Test\TestCase
{
    public function testSetsClientUUIDsEnabledToTrue()
    {
        $this->assertFalse(Visa::$clientUUIDsEnabled);

        Visa::enableClientUUIDs();

        $this->assertTrue(Visa::$clientUUIDsEnabled);

        // Reset configuration
        Visa::$clientUUIDsEnabled = false;
    }
}
