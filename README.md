# Phraseanet API PHP-SDK

[![Build Status](https://secure.travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK.png?branch=master)](http://travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alchemy-fr/Phraseanet-PHP-SDK/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alchemy-fr/Phraseanet-PHP-SDK/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alchemy-fr/Phraseanet-PHP-SDK/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alchemy-fr/Phraseanet-PHP-SDK/?branch=master)

The Phraseanet PHP SDK is an OO library to interact with
[Phraseanet API](https://docs.phraseanet.com/Devel).

## Install

The recommended way to install Phraseanet PHP SDK is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "phraseanet/php-sdk": "~0.6.0"
    }
}
```

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
    echo "Record " . $record->getTitle() . "\n".
    foreach ($record->getSubdefs() as $subdef) {
        echo "subdef ". $subdef->getName() ." has URL " . $subdef->getPermalink()->getUrl() . "\n";
    }
}
```

### Upload files

The Loader is used to upload files to Phraseanet.

```php
$uploader = $app->getUploader($token);

$result = $uploader->upload('/path/to/file.jpg', $base_id);

if ($result instanceof PhraseanetSDK\Entity\Record) {
    // record has been created
} elseif ($result instanceof PhraseanetSDK\Entity\Quarantine) {
    // record has been quarantined
}
```

`$base_id` can be either a `base_id` value or a `PhraseanetSDK\Entity\DataboxCollection`
entity.

Please note that you can force the behavior with the third argument and pass
a Phraseanet record status (binary string) as fourth argument :

```php
$result = $loader->upload('/path/to/file.jpg', $base_id, $behavior, '1011000');
```

Behavior can be either :

 - 0 to force record
 - 1 to force quarantine
 - null to let Phraseanet check (default behavior)

## Configuration

### Extended API Response format

The Phraseanet API (v1.4.1) can provide extended Response format for Record Object.

In this case all relations to Record object (permalink, sub-definitions, caption, status)
are included in the response.

The result is that with this feature you need only one request to populate a whole Record object 
instead of five.

The time to hydrate record object is slightly higher but is ridiculously tiny compared to
the time spent over HTTP protocol to fetch relations data.

```php
$app = PhraseanetSDK\Application::create(array(
    'client-id' => '409ee2762ff49ce936b2ca6e5413607a',
    'secret'    => 'f53ea9b0da92e45f9bbba67439654ac3',
    'url'       => 'https://your.phraseanet-install.com/'
));

$token = '899ee278736b2an6bs786e541ajk8';

// activate globally
$app->getAdapter()->setExtended(true);

// activate for current entity manager
$em = $app->getEntityManager($token);
$em->getAdapter()->setExtended(true);
```

### Log

Request can be logged for monitor or debug purpose by setting a PSR Logger in
the configuration. See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md

See http://guzzle3.readthedocs.org/plugins/log-plugin.html for log plugin configuration

```php
use Psr\Log\LoggerInterface;

class QueryLogger extends LoggerInterface
{
    ...
}
```

```php
use PhraseanetSDK\Application;
use Guzzle\Log\PsrLogAdapter;
use Guzzle\Plugin\Log\LogPlugin;


$client = Application::create(array(
    'client-id' => '409ee2762ff49ce936b2ca6e5413607a',
    'secret'    => 'f53ea9b0da92e45f9bbba67439654ac3',
    'url'       => 'https://your.phraseanet-install.com/'
), array(new LogPlugin(new PsrLogAdapter(new QueryLogger())));
```

### Cache

For performance, it is strongly recommended to use a cache system. This can be
easily done using the following configuration.

See http://guzzle3.readthedocs.org/plugins/cache-plugin.html for cache plugin configuration.

```php
use PhraseanetSDK\Application;
use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\Cache\DefaultCacheStorage;

$cachePlugin = new CachePlugin(array(
    'storage' => new DefaultCacheStorage(
        new DoctrineCacheAdapter(
            new FilesystemCache('/path/to/cache/files')
        )
    )
));

$client = Application::create(
    array(
        'client-id' => '409ee2762ff49ce936b2ca6e5413607a',
        'secret'    => 'f53ea9b0da92e45f9bbba67439654ac3',
        'url'       => 'https://your.phraseanet-install.com/',
    ), array($cachePlugin));
```

## Silex Provider

A [Silex](http://silex.sensiolabs.org/) provider is bundled within this
package.

### Basic usage

```php
$app = new Silex\Application();
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    // required
    'sdk.config' => array(
        'client-id' => $clientId, // Your client id
        'secret'    => $secret, // You client secret
        'url'       => $url, // The ur of the phraseanet instance where you have created your application
    ),
    // optional
    'cache.config' => array(
        'type' => 'array', // can be 'array', 'memcache' or 'memcached'. Default value is 'array'.
        // options for memcache(d) cache type
        'options' => array(
            'host' => '127.0.0.1',
            'port' => '11211'
        )
        'ttl'  => '3600', // cache TTL in seconds. Default value is '3600'.
        'revalidation' => null, // cache re-validation strategy can be null, 'skip' or 'deny' or an object that implements 'Guzzle\Plugin\Cache\RevalidationInterface'
                                // Default value is null.
                                // skip : never performs cache re-validation and just assumes the request is still ok
                                // deny : never performs cache re-validation and just assumes the request is invalid
                                // The default strategy if null is provided is to follow HTTP RFC. see https://tools.ietf.org/html/draft-ietf-httpbis-p4-conditional-26
                                // and https://tools.ietf.org/html/draft-ietf-httpbis-p6-cache-26
        'can_cache' => null,    // can cache strategy can be null or an object that implements 'Guzzle\Plugin\Cache\CanCacheStrategyInterface'
        'key_provider' => null, // key provider strategy can be null or an object that implements 'Guzzle\Plugin\Cache\CacheKeyProviderInterface'
    ),
    'recorder.enabled' => false, // Enabled recorder
    'recorder.config' => array(
        'type' => 'file', // specified type of storage can be 'file', 'memcache' or 'memcached'. Default value is file
        'options' => array(
            'file' => '/path/to/file', // specified path to the file to write data, if specified type is file
            'host' => '127.0.0.1', // specified host to the memcache(d) server , if specified type is memcache or memcached
            'port' => '33', // specified port to the memcache(d) server, if specified type is memcache or memcached
        ),
        'limit' => 1000, // specified limit of request to store
    )
));
```

## Record / Play requests

### Recorder

Recorder can be enabled per requests through service provider using the
following code :

```php
$app = new Silex\Application();
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'sdk.config' => array(
        'client-id' => $clientId,
        'secret'    => $secret,
        'url'       => $url,
    ),
    'recorder.enabled' => true,
    'recorder.config' => array(
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
    'recorder.config' => array(
        'type' => 'memcached',
        'options' => array(
            'host' => '127.0.0.1', 
            'port' => '/path/to/file',
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
example), you must use the `deny` cache re-validation strategy :

```php
$app->register(new PhraseanetSDK\PhraseanetSDKServiceProvider(), array(
    'sdk.config' => array(
        'client-id' => $clientId,
        'secret' => $secret,
        'url' => $url,
    ),
    'cache.config' = array(
        'revalidation' => 'deny',  // important
    )
));
```

## Monitor

SDK provides a tool to monitor Phraseanet :

```php
$monitor = $app->getMonitor($token);
$scheduler = $monitor->getScheduler();

echo sprintf("Scheduler state is %s", $scheduler->getState());
```

## License

Phraseanet SDK is released under the MIT license.
