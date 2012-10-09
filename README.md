Phraseanet API PHP-SDK
======================

[![Build Status](https://secure.travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK.png?branch=master)](http://travis-ci.org/alchemy-fr/Phraseanet-PHP-SDK)

The Phraseanet PHP SDK is an OO library to interact with
[Phraseanet API](https://docs.phraseanet.com/Devel).

#Documentation

Read the documentation at http://phraseanet-php-sdk.readthedocs.org/

#Silex Provider

A [Silex](http://silex.sensiolabs.org/) provider is available at [Phraseanet SDK Silex Provider](https://github.com/alchemy-fr/Phraseanet-PHP-SDK-Silex-Provider)

#Use Example

```php
<?php
use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;

$HttpAdapter = GuzzleAdapter::create();
$HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

$client = new Client($apikey, $apiSecret, $HttpAdapter);
$client->setAccessToken($token);

$em = new EntityManager($client);

$query = $$em->getRepository('Record')->search(array(
    'query' => 'animals'
    'offset_start' => 0,
    'per_page' => 20,
    'bases' => array(1, 4),
    'record_type' => 'image'
));

echo $query->getTotalResults() . " items found in " . $query->getQueryTime() . " seconds\n";

foreach($query->getResults() as $record) {
    echo "Sub definition " . $subdef->getName() . " has URL " . $subdef->getPermalink()->getUrl() . "\n";
}
```

Bim

#License

MIT licensed
