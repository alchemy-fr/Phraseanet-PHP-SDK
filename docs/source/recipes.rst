Recipes
=======

Starter KIT
-----------

As an example you can find a Phraseanet starter kit which provide a set of use and examples
for the SDK.

`composer <http://getcomposer.org/>`_

Extends client
--------------

In most of case you should probably extends the client to handle how access token
is stored on client side.

It's up to you to implement your favorite method (session, database, file etc ..)

Define getAccessToken in the way you want retrieve a previous stored token.

Define setAccessToken in the way you want store your access stoken.

Let's stock our token in session.

.. code-block:: php

    <?php

    //MyClient.php

    class MyClient extends PhraseanetSDK\Client
    {
        protected $session;

        private function initSession()
        {
            if ( ! session_id()) {
                session_start();
            }

            $sessionKey = sprintf('oauth_', $this->apiKey);

            $this->session = &$_SESSION[$sessionKey];
        }

        public function getAccessToken()
        {
            $this->initSession();

            $this->accessToken = $this->session['token'];

            return parent::getAccessToken();
        }

        public function setAccessToken($token)
        {
            $this->initSession();

            $this->session['token'] = $token;

            return parent::setAccessToken($token);
        }
    }

Usage

.. code-block:: php

    <?php

    //index.php

    require_once __DIR__ . '/Myclient.php';

    $httpClient = new  Guzzle\Http\Client();
    $httpClient->setBaseUrl('http://your.instance-api.url/');

    $myClient = new MyClient('Your API Key', 'Your API Secret', $httpClient);

    if(null !== $myClient->getAccessToken()) {
        //user is still authenticated
    } else {
        //force user to authenticate by providing the clicking authorization url
        echo $myClient->getAuthorizationUrl();
    }


Retrieve the last twenty RSS Feed entries
-----------------------------------------

.. code-block:: php

    <?php
    use PhraseanetSDK\Tools\Entity\Manager;

    $em = new Manager($myClient);

    $entryRepository = $em->getRepository('Entry');

    $entries = $entryRepository->findInAggregatedFeed(0, 20);

    foreach($entries as $entry) {
        $output = "======================\n";
        $output .= $entry->getAuthorName() . "\n";
        $output .= $entry->getTitle() . "\n";
        $output .= $entry->getSubTitle() . "\n";
        $output .= $entry->getCreatedOn()->format('d/m/Y H:i:s') . "\n";
    }

Search for records using phraseanet search engine trought API
-------------------------------------------------------------

.. code-block:: php

    <?php

    use PhraseanetSDK\Tools\Entity\Manager;

    $em = new Manager($myClient);

    $recordRepository = $em->getRepository('Record');

    $query = $recordRepository->search(array(
        'query' => 'animals'
        'offset_start' => 0,
        'per_page' => 20,
        'bases' => array(1, 4),
        'record_type' => 'image'
    ));

    echo $query->getTotalResults() . " items found in " . $query->getQueryTime() . " seconds\n";

    foreach($query->getResults() as $record) {
        $output = "======================\n";
        $output .= $record->getTitle() . "\n";
        $output .= $record->getOriginalName() . "\n";
    }

.. note::
    See documentation for possible query parameters
    `https://docs.phraseanet.com/en/Devel/ <https://docs.phraseanet.com/en/Devel/>`_


Retrieve all validation basket
-----------------------------------

.. code-block:: php

    <?php

    use PhraseanetSDK\Tools\Entity\Manager;

    $em = new Manager($myClient);

    $basketRepository = $em->getRepository('Basket');

    $baskets = $basketRepository->findAll();

    foreach($query->getResults()->filter(function($basket){
        return $baket->isValidationBasket();
    }) as $basket) {
        $output = "======================\n";
        $output .= $basket->getName() . "\n";
        $output .= $record->getDescription() . "\n";
    }

.. note::
    ArrayCollection object provides many useful function take a look
    `Doctrine\\Common\\Collections\\ArrayCollection <http://apigen.juzna.cz/doc/doctrine/common/class-Doctrine.Common.Collections.ArrayCollection.html>`_
