<?php

namespace Stratedge\Visa;

use Laravel\Passport\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
