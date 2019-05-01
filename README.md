[![Build Status](https://travis-ci.org/slepic/psr-http-observing-client.svg?branch=master)](https://travis-ci.org/slepic/psr-http-observing-client)
[![Style Status](https://styleci.io/repos/183834781/shield)](https://styleci.io/repos/183834781)

# psr-http-observing-client
PSR ClientInterface implementation that wraps another implementation and observes the transfers using ObserverInterfce from slepic/http-transfer package.

## Requirements

PHP 7

## Installation

Install with composer

```composer require slepic/psr-http-observing-client```

## Usage

Wrap any instance of ```\Psr\Http\Client\ClientInterface``` with ```\Slepic\Psr\Http\ObservingClient\ObservingClient``` and pass it a ```\Slepic\Http\Transfer\Observer\ObserverInterface```.

If you now send all your requests through the ObservingClient, the observer will be notified about start and end of all the transfers.

## Observers

See [```slepic/http-transfer-observer-implementation```] for list of existing observers.
