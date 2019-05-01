<?php


namespace Slepic\Tests\Psr\Http\ObservingClient;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slepic\Http\Transfer\Observer\ObserverDelegateInterface;
use Slepic\Http\Transfer\Observer\ObserverInterface;
use Slepic\Psr\Http\ObservingClient\ObservingClient;

class ObservingClientTest extends TestCase
{
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $observer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $delegate;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $innerClient;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $response;


    protected function setUp()
    {
        parent::setUp();
        $this->request = $this->createMock(RequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->innerClient = $this->createMock(ClientInterface::class);
        $this->observer = $this->createMock(ObserverInterface::class);
        $this->delegate = $this->createMock(ObserverDelegateInterface::class);
        $this->observer->method('observe')
            ->with($this->request)
            ->willReturn($this->delegate);
        $this->client = new ObservingClient($this->innerClient, $this->observer);
    }


    public function testSuccess()
    {
        $this->innerClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->request)
            ->willReturn($this->response);

        $this->observer->expects($this->once())
            ->method('observe')
            ->with($this->request);

        $this->delegate->expects($this->once())
            ->method('success')
            ->with($this->response);

        $response = $this->client->sendRequest($this->request);

        $this->assertSame($this->response, $response);
    }

    public function testError()
    {
        $exception = new \Exception();
        $this->innerClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->request)
            ->willThrowException($exception);

        $this->observer->expects($this->once())
            ->method('observe')
            ->with($this->request);

        $this->delegate->expects($this->once())
            ->method('error')
            ->with($exception);

        $this->expectException(\Exception::class);

        $response = $this->client->sendRequest($this->request);

        $this->assertSame($this->response, $response);
    }
}
