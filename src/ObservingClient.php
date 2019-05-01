<?php


namespace Slepic\Psr\Http\ObservingClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slepic\Http\Transfer\Observer\ObserverInterface;

/**
 * Class ObservingClient
 * @package Slepic\Psr\Http\ObservingClient
 *
 * The observing client observer ongoing transfers using another client to actually process the transfers.
 * ObserverInterface instance is notified about every started and finished transfer.
 */
class ObservingClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ObserverInterface
     */
    private $observer;

    /**
     * ObservingClient constructor.
     * @param ClientInterface $client
     * @param ObserverInterface $observer
     */
    public function __construct(ClientInterface $client, ObserverInterface $observer)
    {
        $this->client = $client;
        $this->observer = $observer;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $delegate = $this->observer->observe($request, ['client' => $this->client]);
        try {
            $response = $this->client->sendRequest($request);
        } catch (\Exception $exception) {
            $delegate->error($exception);
            throw $exception;
        }
        $delegate->success($response);
        return $response;
    }
}
