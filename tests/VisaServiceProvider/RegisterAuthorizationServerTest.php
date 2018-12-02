<?php

namespace Stratedge\Visa\Test\VisaServiceProvider;

use Laravel\Passport\Bridge\PersonalAccessGrant;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use ReflectionClass;

class RegisterAuthorizationServerTest extends \Stratedge\Visa\Test\TestCase
{
    protected function findClassInAuthorizationServer($className)
    {
        $server = $this->app->make(AuthorizationServer::class);

        $class = new ReflectionClass($server);
        $property = $class->getProperty('enabledGrantTypes');
        $property->setAccessible(true);

        $grants = $property->getValue($server);

        $found = false;

        foreach ($grants as $grant) {
            if (is_a($grant, $className)) {
                $found = true;
            }
        }

        return $found;
    }

    public function testShouldEnableAuthCodeGrant()
    {
        $this->assertTrue($this->findClassInAuthorizationServer(AuthCodeGrant::class));
    }

    public function testShouldEnableRefreshTokenGrant()
    {
        $this->assertTrue($this->findClassInAuthorizationServer(RefreshTokenGrant::class));
    }

    public function testShouldEnablePasswordGrant()
    {
        $this->assertTrue($this->findClassInAuthorizationServer(PasswordGrant::class));
    }

    public function testShouldEnablePersonalAccessGrant()
    {
        $this->assertTrue($this->findClassInAuthorizationServer(PersonalAccessGrant::class));
    }

    public function testShouldEnableClientCredentialsGrant()
    {
        $this->assertTrue($this->findClassInAuthorizationServer(ClientCredentialsGrant::class));
    }

    public function testShouldNotEnableImplicitGrantByDefault()
    {
        $this->assertFalse($this->findClassInAuthorizationServer(ImplicitGrant::class));
    }

    public function testShouldEnableImplicitGrantWhenConfigured()
    {
        Passport::enableImplicitGrant();

        $this->assertTrue($this->findClassInAuthorizationServer(ImplicitGrant::class));
    }
}
