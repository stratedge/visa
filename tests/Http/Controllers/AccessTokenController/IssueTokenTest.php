<?php

namespace Stratedge\Visa\Test\Http\Controllers\AccessTokenController;

use Error;
use Exception;
use Illuminate\Http\Response;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Stratedge\Visa\Http\Controllers\AccessTokenController;
use Stratedge\Visa\Visa;
use Throwable;

class IssueTokenTest extends \Stratedge\Visa\Test\TestCase
{
    public function testHandlesExceptionsByDefault()
    {
        $request = $this->app->make(ServerRequestInterface::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['respondToAccessTokenRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('respondToAccessTokenRequest')
            ->will($this->throwException(new Exception));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AccessTokenController::class);

        $result = $controller->issueToken($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());
    }

    public function testHandlesThrowablesByDefault()
    {
        $request = $this->app->make(ServerRequestInterface::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['respondToAccessTokenRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('respondToAccessTokenRequest')
            ->will($this->throwException(new Error));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AccessTokenController::class);

        $result = $controller->issueToken($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());
    }

    public function testHandlesOAuthExceptionsByDefault()
    {
        $request = $this->app->make(ServerRequestInterface::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['respondToAccessTokenRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('respondToAccessTokenRequest')
            ->will($this->throwException(OAuthServerException::unsupportedGrantType()));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AccessTokenController::class);

        $result = $controller->issueToken($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(400, $result->getStatusCode());
    }

    public function testDoesNotCatchExceptionsWhenConfigured()
    {
        Visa::disablePassportErrorHandling();

        $this->expectException(Exception::class);

        $request = $this->app->make(ServerRequestInterface::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['respondToAccessTokenRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('respondToAccessTokenRequest')
            ->will($this->throwException(new Exception));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AccessTokenController::class);

        $controller->issueToken($request);
    }

    public function testDoesNotCatchThrowablesWhenConfigured()
    {
        Visa::disablePassportErrorHandling();

        $this->expectException(Throwable::class);

        $request = $this->app->make(ServerRequestInterface::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['respondToAccessTokenRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('respondToAccessTokenRequest')
            ->will($this->throwException(new Error));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AccessTokenController::class);

        $controller->issueToken($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());
    }

    public function testDoesNotCatchOAuthExceptionsWhenConfigured()
    {
        Visa::disablePassportErrorHandling();

        $this->expectException(OAuthServerException::class);

        $request = $this->app->make(ServerRequestInterface::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['respondToAccessTokenRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('respondToAccessTokenRequest')
            ->will($this->throwException(OAuthServerException::unsupportedGrantType()));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AccessTokenController::class);

        $controller->issueToken($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(400, $result->getStatusCode());
    }
}
