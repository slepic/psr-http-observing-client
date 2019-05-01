[![Build Status](https://travis-ci.org/slepic/psr-http-observing-client.svg?branch=master)](https://travis-ci.org/slepic/psr-http-observing-client)
[![Style Status](https://styleci.io/repos/184448040/shield)](https://styleci.io/repos/184448040)

# psr-http-observing-client
PSR ClientInterface implementation that wraps another implementation and observes the transfers using ObserverInterfce from slepic/http-transfer package.

## Requirements

PHP 7

## Installation

Install with composer

```composer require slepic/psr-http-observing-client```

## Usage

Wrap any instance of [```\Psr\Http\Client\ClientInterface```](https://github.com/php-fig/http-client/blob/master/src/ClientInterface.php) with the [```\Slepic\Psr\Http\ObservingClient\ObservingClient```](https://github.com/slepic/psr-http-observing-client/blob/master/src/ObservingClient.php) and pass it a [```\Slepic\Http\Transfer\Observer\ObserverInterface```](https://github.com/slepic/http-transfer/blob/master/src/Observer/ObserverInterface.php).

If you now send all your requests through the ObservingClient, the observer will be notified about start and end of all the transfers.

```
$observer = new HistoryObserver(new ArrayStorage());
$psrClient = new SomePsrClient();
$client = new ObservingClient($psrClient, $observer);

try {
$response = $client->sendRequest($request);
} catch (\Exception $e) {
  assert($storage[0]->getRequest() === $request);
  assert($storage[0]->getException() === $e);
  throw $e;
}

assert($storage[0]->getRequest() === $request);
assert($storage[0]->getResponse() === $response);

```


## Observers

See [```slepic/http-transfer-observer-implementation```](https://packagist.org/providers/slepic/http-transfer-observer-implementation) for list of existing observers.
