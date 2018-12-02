<?php

namespace Stratedge\Visa\Http\Controllers;

use Laravel\Passport\Http\Controllers\HandlesOAuthErrors as BaseHandleOAuthErrors;
use Stratedge\Visa\Visa;

trait HandlesOAuthErrors
{
    use BaseHandleOAuthErrors {
        BaseHandleOAuthErrors::withErrorHandling as parentWithErrorHandling;
    }

    /**
     * Perform the given callback, without exception handling by default, but
     * with the ability to use it when configured.
     *
     * @param  \Closure  $callback
     * @return \Illuminate\Http\Response
     */
    protected function withErrorHandling($callback)
    {
        if (Visa::$passportErrorHandlingEnabled !== true) {
            return $callback();
        } else {
            return parent::withErrorHandling($callback);
        }
    }
}
