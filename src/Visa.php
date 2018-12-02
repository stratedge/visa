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
     * Indicates if the default Passport error handling should be used for OAuth
     * errors.
     *
     * @var boolean
     */
    public static $passportErrorHandlingEnabled = false;

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

    /**
     * Configure Visa to use the default Passport error handling for OAuth
     * errors.
     *
     * @return static
     */
    public static function enablePassportErrorHandling()
    {
        static::$passportErrorHandlingEnabled = true;

        return new static;
    }
}
