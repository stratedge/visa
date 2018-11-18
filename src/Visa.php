<?php

namespace Stratedge\Visa;

class Visa
{
    /**
     * Indicates if UUIDs should be used for client IDs.
     *
     * @var boolean
     */
    public static $clientUUIDsEnabled = false;

    /**
     * Configure Visa to use UUIDs for client IDs.
     *
     * @return static
     */
    public static function enableClientUUIDs()
    {
        static::$clientUUIDsEnabled = true;

        return new static;
    }
}
