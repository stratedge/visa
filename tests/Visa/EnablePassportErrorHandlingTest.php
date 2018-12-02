<?php

namespace Stratedge\Visa\Test;

use Stratedge\Visa\Visa;

class EnablePassportErrorHandlingTest extends \Stratedge\Visa\Test\TestCase
{
    public function testSetsPassportErrorHandlingEnabledToTrue()
    {
        $this->assertFalse(Visa::$passportErrorHandlingEnabled);

        Visa::enablePassportErrorHandling();

        $this->assertTrue(Visa::$passportErrorHandlingEnabled);

        // Reset configuration
        Visa::$passportErrorHandlingEnabled = false;
    }
}
