# Phraseanet API PHP-SDK

[![Build Status](https://secure.travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK.png?branch=master)](http://travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK)

The Phraseanet PHP SDK is an OO library to interact with
[Phraseanet API](https://docs.phraseanet.com/Devel).

#Documentation

Read the documentation at http://phraseanet-php-sdk.readthedocs.org/

## Basic Usage

### Create the client

Here is the minimum to create the Phraseanet client ; please
note that client `key`, `secret` and `token` a required. Please refer to
the [online documentation](https://docs.phraseanet.com/3.7/en/Devel/ApplicationDeveloper.html)
to get more more information about getting those.

```php
use PhraseanetSDK\Client;

$client = Client::create(array(
    'key'    => '409ee2762ff49ce936b2ca6e5413607a',     // aka Client ID
    'secret' => 'f53ea9b0da92e45f9bbba67439654ac3',     // aka Client Secret
    'token'  => 'd855d9f66774eda3b67101055b03c1d6',     // aka Token
    'url'    => 'https://your.phraseanet-install.com/', // Phraseanet install URI
));
```

### Use the EntityManager

The `EntityManager` is the entry point to retrieve Phraseanet entities.

```php
$em = $client->getEntityManager();

$query = $em->getRepository('Record')->search(array(
    'query'        => 'animals',
    'offset_start' => 0,
    'per_page'     => 20,
    'bases'        => array(1, 4),
    'record_type'  => 'image'
));

echo $query->getTotalResults() . " items found in " . $query->getQueryTime() . " seconds\n";

foreach($query->getResults() as $record) {
    echo "Sub definition " . $subdef->getName() . " has URL " . $subdef->getPermalink()->getUrl() . "\n";
}
```

## Advanced Usage

### Log

Request can be logged for monitor or debug purpose by setting a PSR Logger in
the configuration.

```php
$client = Client::create(array(
    'key'    => '409ee2762ff49ce936b2ca6e5413607a',
    'secret' => 'f53ea9b0da92e45f9bbba67439654ac3',
    'token'  => 'd855d9f66774eda3b67101055b03c1d6',
    'url'    => 'https://your.phraseanet-install.com/',
    'logger' => $logger,
));
```

### Cache

For performance, it is strongly recommended to use a cache system. This can be
easily done using the following configuration.

```php
$client = Client::create(array(
    'key'    => '409ee2762ff49ce936b2ca6e5413607a',
    'secret' => 'f53ea9b0da92e45f9bbba67439654ac3',
    'token'  => 'd855d9f66774eda3b67101055b03c1d6',
    'url'    => 'https://your.phraseanet-install.com/',
    'cache'  => array(
        'type'       => 'memcached', // cache type
        'host'       => '127.0.0.1', // cache server host
        'port'       => 11211,       // cache server port
        'lifetime'   => 300,         // cache lifetime in seconds
        'revalidate' => 'skip',      // cache revalidation strategy
    ),
));
```

Parameters can be configured as follow :

 - type : either `array`, `memcache` or `memcached`.
 - revalidate : either `skip` or `deny`.

`skip` revalidation strategy will always use the data from cache if present,
while `deny` will always consider cached data as invalid.

## Silex Provider

A [Silex](http://silex.sensiolabs.org/) provider is bundled within this
package.

### Basic usage

```php
$app = new Silex\Application();
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.apiKey'      => $key,
    'phraseanet-sdk.apiSecret'   => $secret,
    'phraseanet-sdk.apiUrl'      => $url,
    'phraseanet-sdk.apiDevToken' => $token,
));
```

### Configure cache and log

If no logger is passed and MonologServiceProvider registered, `$app['monolog']`
service is used as a logger.

```php
$app = new Silex\Application();

$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.apiKey'           => $key,
    'phraseanet-sdk.apiSecret'        => $secret,
    'phraseanet-sdk.apiUrl'           => $url,
    'phraseanet-sdk.apiDevToken'      => $token,
    'phraseanet-sdk.cache'            => 'memcached',
    'phraseanet-sdk.cache_host'       => 'localhost',
    'phraseanet-sdk.cache_port'       => 11211,
    'phraseanet-sdk.cache_ttl'        => 300,
    'phraseanet-sdk.cache_revalidate' => 'skip',
    'phraseanet-sdk.logger'           => $logger
));
```

## License

Phraseanet SDK is released under the MIT license.
