<?php

namespace Stratedge\Visa\Test;

use Stratedge\Visa\Visa;

class DisablePassportErrorHandlingTest extends \Stratedge\Visa\Test\TestCase
{
    public function testSetsPassportErrorHandlingDisabledToTrue()
    {
        $this->assertFalse(Visa::$passportErrorHandlingDisabled);

        Visa::disablePassportErrorHandling();

        $this->assertTrue(Visa::$passportErrorHandlingDisabled);

        // Reset configuration
        Visa::$passportErrorHandlingDisabled = false;
    }
}
