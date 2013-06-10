# Phraseanet API PHP-SDK

[![Build Status](https://secure.travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK.png?branch=master)](http://travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK)

The Phraseanet PHP SDK is an OO library to interact with
[Phraseanet API](https://docs.phraseanet.com/Devel).

## Basic Usage

### Create the application

Here is the minimum to create the Phraseanet SDK Application ; please
note that client `client-id`, `url` and `secret` a required. Please refer to
the [online documentation](https://docs.phraseanet.com/3.7/en/Devel/ApplicationDeveloper.html)
to get more more information about generating those.

```php
$app = PhraseanetSDK\Application::create(array(
    'client-id' => '409ee2762ff49ce936b2ca6e5413607a',
    'secret'    => 'f53ea9b0da92e45f9bbba67439654ac3',
    'url'       => 'https://your.phraseanet-install.com/', // Phraseanet install URI
));
```

### Getting an oauth token

Once the application is created, a token is required to query the API. There are
two ways :

#### Developer token

The developer token can be retrieved from Phraseanet developer application
panel (My account > developer > applications).

#### OAuth2 authentication flow

Phraseanet SDK provides a convenient way to retrieve an oauth token. Use the
OAuth2Connector for that :

- Redirect the end user to the Phraseanet authorization URL :

```php
$connector = $app->getOauth2Connector();
$url = $connector->getAuthorizationUrl($redirectUri); // must be the same as the one declared
                                                      // in the application your created in Phraseanet
```

Note that extra parameters can be passed to the `getAuthorizationUrl` method.
Please refer to the [online documentation](https://docs.phraseanet.com/Devel)
about available parameters.

- Retrieve the access token in you application callback :

```php
$connector = $app->getOauth2Connector();
$token = $connector->retrieveAccessToken($code, $redirectUri);
```

Once you have the token, you can use the `EntityManager`.

### Use the EntityManager

The `EntityManager` is the entry point to retrieve Phraseanet entities.

```php
$em = $app->getEntityManager($token);

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

## Configuration

### Log

Request can be logged for monitor or debug purpose by setting a PSR Logger in
the configuration.

```php
$client = Client::create(array(
    'client-id' => '409ee2762ff49ce936b2ca6e5413607a',
    'secret'    => 'f53ea9b0da92e45f9bbba67439654ac3',
    'url'       => 'https://your.phraseanet-install.com/',
    'logger'    => $logger,
));
```

### Cache

For performance, it is strongly recommended to use a cache system. This can be
easily done using the following configuration.

```php
$client = Client::create(
    array(
        'client-id' => '409ee2762ff49ce936b2ca6e5413607a',
        'secret'    => 'f53ea9b0da92e45f9bbba67439654ac3',
        'url'       => 'https://your.phraseanet-install.com/',
    ), array(
        'type'       => 'memcached', // cache type
        'host'       => '127.0.0.1', // cache server host
        'port'       => 11211,       // cache server port
        'lifetime'   => 300,         // cache lifetime in seconds
    )
));
```

Cache parameters can be configured as follow :

 - type : either `array`, `memcache` or `memcached`.

## Silex Provider

A [Silex](http://silex.sensiolabs.org/) provider is bundled within this
package.

### Basic usage

```php
$app = new Silex\Application();
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.config' => array(
        'client-id' => $clientId,
        'secret'    => $secret,
        'url'       => $url,
    ),
));
```

### Configure cache and log

```php
$app = new Silex\Application();

$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.config' => array(
        'client-id' => $clientId,
        'secret'    => $secret,
        'url'       => $url,
        'logger'    => $logger,
    ),
    'phraseanet-sdk.cache.config' = array(
        'type' => 'memcached',
        'host' => 'localhost',
        'port' => 11211,
        'ttl'  => 300,
    ),
));
```

## Record / Play requests

### Recorder

Recorder can be enabled per requests through service provider using the
following code :

```php
$app = new Silex\Application();
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.config' => array(
        'client-id' => $clientId,
        'secret'    => $secret,
        'url'       => $url,
    ),
    'phraseanet-sdk.recorder.enabled' => true,
    'phraseanet-sdk.recorder.config' => array(
        'type' => 'memcached',
        'options' => array(
            'host' => 'localhost',
            'port' => 11211,
        ),
        'limit' => 5000 // record up to 5000 requests
    ),
));
```

Requests can be store either in `memcache`, `memcached` or `file`. To use file,
configuration should look like :

```php
$app = new Silex\Application();
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.recorder.config' => array(
        'type' => 'memcached',
        'options' => array(
            'file' => '/path/to/logfile.json',
        ),
        'limit' => 5000 // record up to 5000 requests
    ),
));
```

### Player

To replay stored requests, use the player

```php
$player = $app['phraseanet-sdk.player.factory']($token);
$player->play();
```

Please note that, in order to play request without using cache (to warm it for
example), you must use the `deny` cache revalidation strategy :

```php
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'phraseanet-sdk.config' => array(
        'client-id' => $clientId,
        'secret' => $secret,
        'url' => $url,
    ),
    'phraseanet-sdk.cache.config' = array(
        'type' => 'memcached',
        'host' => 'localhost',
        'port' => 11211,
        'ttl'  => 300,
        'revalidate' => 'deny',  // important
    )
));
```

## License

Phraseanet SDK is released under the MIT license.
