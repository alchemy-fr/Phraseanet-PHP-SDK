<?php

namespace Test;

use Guzzle\Http\Client;
use Guzzle\Http\Plugin\MockPlugin;
use Guzzle\Http\Message\Response;
use PhraseanetSDK\HttpAdapter\Guzzle as Adapter;

class GuzzleClientTest extends AbstractClient
{

    public function getAdapter($response = null, $code = 200)
    {
        $plugin = new MockPlugin();

        $plugin->addResponse(new Response($code, null, $response));

        $clientHttp = $this->getClient();

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        return new Adapter($clientHttp);
    }

    private function getClient()
    {
        return new Client('http://my.domain.tld/', array('version' => 1));
    }
}
