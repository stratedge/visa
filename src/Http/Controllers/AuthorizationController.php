<?php

namespace Stratedge\Visa\Http\Controllers;

use Laravel\Passport\Http\Controllers\AuthorizationController as BaseController;
use Stratedge\Visa\Http\Controllers\HandlesOAuthErrors;

class AuthorizationController extends BaseController
{
    use HandlesOAuthErrors;
}
