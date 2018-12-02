<?php

namespace Stratedge\Visa\Test\Http\Controllers\ApproveAuthorizationController;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Store;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Stratedge\Visa\Http\Controllers\ApproveAuthorizationController;
use Stratedge\Visa\Visa;
use Throwable;

class ApproveTest extends \Stratedge\Visa\Test\TestCase
{
    protected function setUpRequest()
    {
        $authRequest = $this->createMock(AuthorizationRequest::class);

        $session = $this->createMock(Store::class);
        $session->expects($this->once())
            ->method('get')
            ->willReturn($authRequest);

        $user = $this->createMock(Model::class);
        $user->expects($this->once())
            ->method('getKey')
            ->willReturn('testing');

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['session', 'user'])
            ->getMock();

        $request->expects($this->once())
            ->method('session')
            ->willReturn($session);

        $request->expects($this->once())
            ->method('user')
            ->willReturn($user);

        return $request;
    }

    public function testDoesNotCatchExceptionsByDefault()
    {
        $this->expectException(Exception::class);

        $request = $this->setUpRequest();

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['completeAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('completeAuthorizationRequest')
            ->will($this->throwException(new Exception));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(ApproveAuthorizationController::class);

        $controller->approve($request);
    }

    public function testDoesNotCatchThrowablesByDefault()
    {
        $this->expectException(Throwable::class);

        $request = $this->setUpRequest();

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['completeAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('completeAuthorizationRequest')
            ->will($this->throwException(new Error));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(ApproveAuthorizationController::class);

        $controller->approve($request);
    }

    public function testDoesNotCatchOAuthExceptionsByDefault()
    {
        $this->expectException(OAuthServerException::class);

        $request = $this->setUpRequest();

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['completeAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('completeAuthorizationRequest')
            ->will($this->throwException(OAuthServerException::unsupportedGrantType()));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(ApproveAuthorizationController::class);

        $controller->approve($request);
    }

    public function testHandlesExceptionsWhenConfigured()
    {
        Visa::enablePassportErrorHandling();

        $request = $this->setUpRequest();

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['completeAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('completeAuthorizationRequest')
            ->will($this->throwException(new Exception));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(ApproveAuthorizationController::class);

        $result = $controller->approve($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());

        // Reset configuration
        Visa::$passportErrorHandlingEnabled = false;
    }

    public function testHandlesThrowablesWhenConfigured()
    {
        Visa::enablePassportErrorHandling();

        $request = $this->setUpRequest();

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['completeAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('completeAuthorizationRequest')
            ->will($this->throwException(new Error));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(ApproveAuthorizationController::class);

        $result = $controller->approve($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(500, $result->getStatusCode());

        // Reset configuration
        Visa::$passportErrorHandlingEnabled = false;
    }

    public function testHandlesOAuthExceptionsWhenConfigured()
    {
        Visa::enablePassportErrorHandling();

        $request = $this->setUpRequest();

        $server = $this->getMockBuilder(AuthorizationServer::class)
            ->disableOriginalConstructor()
            ->setMethods(['completeAuthorizationRequest'])
            ->getMock();

        $server->expects($this->once())
            ->method('completeAuthorizationRequest')
            ->will($this->throwException(OAuthServerException::unsupportedGrantType()));

        $this->app->instance(AuthorizationServer::class, $server);

        $controller = $this->app->make(ApproveAuthorizationController::class);

        $result = $controller->approve($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame(400, $result->getStatusCode());

        // Reset configuration
        Visa::$passportErrorHandlingEnabled = false;
    }
}
