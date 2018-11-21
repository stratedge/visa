<?php

namespace Stratedge\Visa\Test\Http\Middleware\CheckFirstPartyClient;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Stratedge\Visa\Client;
use Stratedge\Visa\ClientRepository;
use Stratedge\Visa\Http\Middleware\CheckFirstPartyClient;

class HandleTest extends \Stratedge\Visa\Test\TestCase
{
    public function testValidationFailureThrowsException()
    {
        $this->expectException(AuthenticationException::class);

        $server = $this->getMockBuilder(ResourceServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthenticatedRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthenticatedRequest')
            ->will($this->throwException(new OAuthServerException('', 0, '')));

        $repository = $this->app->make(ClientRepository::class);

        $request = $this->app->make(Request::class);

        $closureCalled = false;

        $closure = function () use (&$closureCalled) {
            $closureCalled = true;
        };

        $middleware = new CheckFirstPartyClient($server, $repository);

        $middleware->handle($request, $closure);

        $this->assertFalse($closureCalled);
    }

    public function testNoClientThrowsException()
    {
        $this->expectException(AuthenticationException::class);

        $psr = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['getAttribute'])
            ->getMock();

        $psr->expects($this->once())
            ->method('getAttribute')
            ->willReturn('abc123');

        $server = $this->getMockBuilder(ResourceServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthenticatedRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthenticatedRequest')
            ->willReturn($psr);

        $repository = $this->getMockBuilder(ClientRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $repository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $request = $this->app->make(Request::class);

        $closureCalled = false;

        $closure = function () use (&$closureCalled) {
            $closureCalled = true;
        };

        $middleware = new CheckFirstPartyClient($server, $repository);

        $middleware->handle($request, $closure);

        $this->assertFalse($closureCalled);
    }

    public function testNonFirstPartyClientThrowsException()
    {
        $this->expectException(AuthenticationException::class);

        $psr = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['getAttribute'])
            ->getMock();

        $psr->expects($this->once())
            ->method('getAttribute')
            ->willReturn('abc123');

        $server = $this->getMockBuilder(ResourceServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthenticatedRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthenticatedRequest')
            ->willReturn($psr);

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['firstParty'])
            ->getMock();

        $client->expects($this->once())
            ->method('firstParty')
            ->willReturn(false);

        $repository = $this->getMockBuilder(ClientRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $repository->expects($this->once())
            ->method('find')
            ->willReturn($client);

        $request = $this->app->make(Request::class);

        $closureCalled = false;

        $closure = function () use (&$closureCalled) {
            $closureCalled = true;
        };

        $middleware = new CheckFirstPartyClient($server, $repository);

        $middleware->handle($request, $closure);

        $this->assertFalse($closureCalled);
    }

    public function testFirstPartyClientCallsNextItemInStack()
    {
        $psr = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['getAttribute'])
            ->getMock();

        $psr->expects($this->once())
            ->method('getAttribute')
            ->willReturn('abc123');

        $server = $this->getMockBuilder(ResourceServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateAuthenticatedRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('validateAuthenticatedRequest')
            ->willReturn($psr);

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['firstParty'])
            ->getMock();

        $client->expects($this->once())
            ->method('firstParty')
            ->willReturn(true);

        $repository = $this->getMockBuilder(ClientRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $repository->expects($this->once())
            ->method('find')
            ->willReturn($client);

        $request = $this->app->make(Request::class);

        $closureCalled = false;

        $closure = function () use (&$closureCalled) {
            $closureCalled = true;
            return 'called';
        };

        $middleware = new CheckFirstPartyClient($server, $repository);

        $this->assertSame('called', $middleware->handle($request, $closure));

        $this->assertTrue($closureCalled);
    }
}
