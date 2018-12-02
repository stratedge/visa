<?php

namespace Stratedge\Visa\Http\Controllers;

use Laravel\Passport\Http\Controllers\ApproveAuthorizationController as BaseController;
use Stratedge\Visa\Http\Controllers\HandlesOAuthErrors;

class ApproveAuthorizationController extends BaseController
{
    use HandlesOAuthErrors;
}
