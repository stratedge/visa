<?php

namespace Stratedge\Visa\Test\Http\Controllers\AuthorizationController;

use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Stratedge\Visa\Http\Controllers\AuthorizationController;
use Stratedge\Visa\Visa;
use Throwable;

class AuthorizeTest extends \Stratedge\Visa\Test\TestCase
{
    public function testHandlesExceptionsByDefault()
    {
        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthorizationRequest')
            ->will($this->throwException(new Exception));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AuthorizationController::class);

        $result = $controller->authorize(
            $this->app->make(ServerRequestInterface::class),
            $this->app->make(Request::class),
            $this->app->make(ClientRepository::class),
            $this->app->make(TokenRepository::class)
        );

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());
    }

    public function testHandlesThrowablesByDefault()
    {
        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthorizationRequest')
            ->will($this->throwException(new Error));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AuthorizationController::class);

        $result = $controller->authorize(
            $this->app->make(ServerRequestInterface::class),
            $this->app->make(Request::class),
            $this->app->make(ClientRepository::class),
            $this->app->make(TokenRepository::class)
        );

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());
    }

    public function testHandlesOAuthExceptionsByDefault()
    {
        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthorizationRequest')
            ->will($this->throwException(OAuthServerException::unsupportedGrantType()));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AuthorizationController::class);

        $result = $controller->authorize(
            $this->app->make(ServerRequestInterface::class),
            $this->app->make(Request::class),
            $this->app->make(ClientRepository::class),
            $this->app->make(TokenRepository::class)
        );

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(400, $result->getStatusCode());
    }

    public function testDoesNotCatchExceptionsWhenConfigured()
    {
        Visa::disablePassportErrorHandling();

        $this->expectException(Exception::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthorizationRequest')
            ->will($this->throwException(new Exception));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AuthorizationController::class);

        $controller->authorize(
            $this->app->make(ServerRequestInterface::class),
            $this->app->make(Request::class),
            $this->app->make(ClientRepository::class),
            $this->app->make(TokenRepository::class)
        );
    }

    public function testDoesNotCatchThrowablesWhenConfigured()
    {
        Visa::disablePassportErrorHandling();

        $this->expectException(Throwable::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthorizationRequest')
            ->will($this->throwException(new Error));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AuthorizationController::class);

        $controller->authorize(
            $this->app->make(ServerRequestInterface::class),
            $this->app->make(Request::class),
            $this->app->make(ClientRepository::class),
            $this->app->make(TokenRepository::class)
        );
    }

    public function testDoesNotCatchOAuthExceptionsWhenConfigured()
    {
        Visa::disablePassportErrorHandling();

        $this->expectException(OAuthServerException::class);

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthorizationRequest')
            ->will($this->throwException(OAuthServerException::unsupportedGrantType()));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(AuthorizationController::class);

        $controller->authorize(
            $this->app->make(ServerRequestInterface::class),
            $this->app->make(Request::class),
            $this->app->make(ClientRepository::class),
            $this->app->make(TokenRepository::class)
        );
    }
}
