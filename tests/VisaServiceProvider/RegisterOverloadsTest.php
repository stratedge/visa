<?php

namespace Stratedge\Visa\Test;

use Laravel\Passport\ClientRepository as BaseRepository;
use Stratedge\Visa\ClientRepository;
use Stratedge\Visa\Http\Controllers\AccessTokenController;
use Stratedge\Visa\Http\Controllers\ApproveAuthorizationController;
use Stratedge\Visa\Http\Controllers\AuthorizationController;

class RegisterOverloadsTest extends \Stratedge\Visa\Test\TestCase
{
    public function testOverridesClientRepository()
    {
        $clientRepository = $this->app->make(BaseRepository::class);
        $this->assertInstanceOf(ClientRepository::class, $clientRepository);
    }

    public function testOverridesAccessTokenController()
    {
        $controller = $this->app->make(AccessTokenController::class);
        $this->assertInstanceOf(AccessTokenController::class, $controller);
    }

    public function testOverridesApproveAuthorizationController()
    {
        $controller = $this->app->make(ApproveAuthorizationController::class);
        $this->assertInstanceOf(ApproveAuthorizationController::class, $controller);
    }

    public function testOverridesAuthorizationController()
    {
        $controller = $this->app->make(AuthorizationController::class);
        $this->assertInstanceOf(AuthorizationController::class, $controller);
    }
}
