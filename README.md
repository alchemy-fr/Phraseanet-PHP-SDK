# Phraseanet API PHP-SDK

[![License](https://img.shields.io/packagist/l/phraseanet/php-sdk.svg?style=flat-square)](https://github.com/alchemy-fr/Phraseanet-PHP-SDK/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/phraseanet/php-sdk.svg?style=flat-square)](https://packagist.org/packages/phraseanet/php-sdk)
[![Travis](https://img.shields.io/travis/alchemy-fr/Phraseanet-PHP-SDK.svg?style=flat-square)](https://travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/alchemy-fr/Phraseanet-PHP-SDK.svg?style=flat-square)](https://scrutinizer-ci.com/g/alchemy-fr/Phraseanet-PHP-SDK/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/alchemy-fr/Phraseanet-PHP-SDK.svg?style=flat-square)](https://scrutinizer-ci.com/g/alchemy-fr/Phraseanet-PHP-SDK/)
[![Packagist](https://img.shields.io/packagist/dt/phraseanet/php-sdk.svg?style=flat-square)](https://packagist.org/packages/phraseanet/php-sdk/stats)

The Phraseanet PHP SDK is an OO library to interact with
[Phraseanet API](https://docs.phraseanet.com/Devel).

## Install

The recommended way to install Phraseanet PHP SDK is [through composer](http://getcomposer.org).

```bash
composer require phraseanet/php-sdk:^1.0
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
// Must be identical to the redirect URI set in your Oauth application configuration in Phraseanet.
$redirectUri = 'http://myhost.dev/oauth-callback-endpoint/';
$connector = $app->getOauth2Connector();
$url = $connector->getAuthorizationUrl($redirectUri);
```

Note that extra parameters can be passed to the `getAuthorizationUrl` method.
Please refer to the [online documentation](https://docs.phraseanet.com/Devel)
about available parameters.

- Retrieve an access token in your application callback :

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

### Uploading files to Phraseanet

Files can be uploaded to Phraseanet using the uploader instance exposed via the `Application` object:

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



## Monitor

SDK provides a tool to monitor Phraseanet :

```php
$monitor = $app->getMonitor($token);
$scheduler = $monitor->getScheduler();

echo sprintf("Scheduler state is %s", $scheduler->getState());
```

## License

Phraseanet SDK is released under the MIT license.
