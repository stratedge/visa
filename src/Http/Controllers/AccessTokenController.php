<?php

namespace Stratedge\Visa\Http\Controllers;

use Laravel\Passport\Http\Controllers\AccessTokenController as BaseController;
use Stratedge\Visa\Http\Controllers\HandlesOAuthErrors;

class AccessTokenController extends BaseController
{
    use HandlesOAuthErrors;
}
