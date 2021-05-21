# Bitvavo Client for PHP

This is a work in progress.

All trades on the Bitvavo platform are processed based on
[Trading Rules](https://bitvavo.com/en/trading-rules). Please
read this document to understand more about the nitty gritty
of fees, orders and other parameters.

We want to be 100% clear. Using this client is at your own
risk. Bitvavo and the makers of this package are not liable
for any potential damages caused. We provide no warranties
whatsoever, neither explicit or implicit.

## Installation

```bash
composer install bitvavo/bitvavo-client
```

## Usage

```php
include 'vendor/autoload.php';

use Bitvavo\Bitvavo;

$bitvavo = new Bitvavo(apiKey: $apiKey, apiSecret: $apiSecret);
Bitvavo::setInstance($bitvavo);
```

This will give you access to the Bitvavo API through `$bitvavo`. However,
we do recommend using Models to fetch information from the API. For
example:

```php
$market = Market::make(coin: 'BTC', currency: 'EUR');
$all = Trade::market($market)->all();
```

This will give you all Trades, based on the `BTC-EUR` market.

## Dates & Timezones

Bitvavo returs timestamps, not dates. As timestamps are hard(er) to work
with when developing applications, we try to append a public property
called `date`. This will be a [Carbon](https://carbon.nesbot.com/) object.

By default, timestamps are returned as UTC. This can be practical, but
it isn't when your displaying dates. The `date` property is converted
to the `Europe/Amsterdam` timezone by default. If you want to change
this, you can use:

```php
Bitvavo::setTimezone('America/Toronto')
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.
