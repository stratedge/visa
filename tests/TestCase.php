<?php

namespace Stratedge\Visa\Test;

use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Stratedge\Wye\Wye;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Stratedge\Visa\VisaServiceProvider'];
    }

    public function setUp()
    {
        Wye::reset();

        Connection::resolverFor('mysql', function () {
            return new MySqlConnection(Wye::makePDO());
        });

        parent::setUp();
    }
}
