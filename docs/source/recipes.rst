Recipes
=======

How to check if you are connected to the API ?
----------------------------------------------

There is not a dedicated method to test if you are actually connected to the API.

However if you want to test if the connection can be established you must
perform a dummy request to the remote instance and check if the response is ok.

.. code-block:: php

    <?php
    use PhraseanetSDK\EntityManager;
    use PhraseanetSDK\Client;
    use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;
    use PhraseanetSDK\Exception\UnauthorizedException;

    $HttpAdapter = GuzzleAdapter::create();
    $HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

    $client = new Client($apikey, $apiSecret, $HttpAdapter);
    $client->setAccessToken($token);

    $em = new EntityManager($client);

    $databoxRepository = $em->getRepository('Databox');

    try {
      $databoxRepository->findAll();
    } catch (UnauthorizedException $e) {
          // Connection is not valid, handle it
    } catch (\Exception $e) {
          // Something else went wrong
    }

Retrieve the last twenty Feed entries
-------------------------------------

This code retrieves the 20 latest feed entries and print some informations
about it.

.. code-block:: php

    <?php
    use PhraseanetSDK\EntityManager;
    use PhraseanetSDK\Client;
    use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;

    $HttpAdapter = GuzzleAdapter::create();
    $HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

    $client = new Client($apikey, $apiSecret, $HttpAdapter);
    $client->setAccessToken($developerToken);

    $em = new EntityManager($client);

    $entries = $em->getRepository('Entry')->findInAggregatedFeed(0, 20);

    foreach($entries as $entry) {
        $output = "======================\n";
        $output .= $entry->getAuthorName() . "\n";
        $output .= $entry->getTitle() . "\n";
        $output .= $entry->getSubTitle() . "\n";
        $output .= $entry->getCreatedOn()->format('d/m/Y H:i:s') . "\n";
    }


Search for records
------------------

The following code search for records

.. code-block:: php

    <?php
    use PhraseanetSDK\EntityManager;
    use PhraseanetSDK\Client;
    use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;

    $HttpAdapter = GuzzleAdapter::create();
    $HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

    $client = new Client($apikey, $apiSecret, $HttpAdapter);
    $client->setAccessToken($token);
    $_SESSION['token_phrasea'] = $token;


    $em = new EntityManager($client);

    $recordRepository = $em->getRepository('Record');

    $query = $recordRepository->search(array(
        'query' => 'animals',
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
    use PhraseanetSDK\EntityManager;

    $em = new EntityManager($myClient);

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

oAuth2 Authentication Flow
--------------------------

**How to get a token from the API ?**

Phraseanet API only supports 'Token Grant Type'.

With this grant type you redirect the user to an authorization page on
Phraseanet, and your script is called back once the end-user authorized your API
key to access the Phraseanet service on its behalf.

**Authorization page**

.. code-block:: php

    <?php

    $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION, array('redirect_uri' => 'YOUR_REDIRECT_URI'));

    // output the authentication url to the end user
    echo $client->getAuthorizationUrl();

.. note::
    In case your authorization page is the same that your callback page

**Callback page**

.. code-block:: php

    <?php
    use Symfony\Component\HttpFoundation\Request;
    use PhraseanetSDK\Exception\AuthenticationException;
    use PhraseanetSDK\Exception\RuntimeException;

    $request = Request::createFromGlobals();

    // retrieve the access token from current request
    try {
        $client->retrieveAccessToken($request);
    } catch (AuthenticationException $e) {
        // Something went wrong during the authentication flow
    } catch (RuntimeException $e) {
        // Something went wrong for obscur reasons during the retrieval of the token
    }

.. note::
    ACCESS_TOKEN does not expire.
    So once you have an ACCESS_TOKEN associated to your current user,
    you can manage user's token with your own storage system on top of the
    library or you can just extends the PhraseanetSDK\Client object an override
    the *getAccessToken* and *setAccessToken* method. See the next example to
    store token.

Store clients token in session
------------------------------

In some case you would probably store clients token in the session or database.
SDK provide a StoreInterface for that :
Let's store our token in ``session``.

.. code-block:: php

    <?php

    namespace Acme\Application\Phrasea

    use PhraseanetSDK\Authentication\StoreInterface;

    class SessionStore implements StoreInterface
    {
        protected $token;

        public function __construct()
        {
            $this->initSession();
        }

        public function saveToken($token)
        {
            $this->token = $token;
        }

        public function getToken()
        {
            return $this->token;
        }

        private function initSession()
        {
            if ( ! session_id()) {
                session_start();
            }

            $this->token = &$_SESSION['phrasea_oauth_token'];
        }
    }

Usage

.. code-block:: php

    <?php

    use Acme\Application\Phrasea\SessionStore;
    use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;
    use PhraseanetSDK\Client;

    $HttpAdapter = GuzzleAdapter::create();
    $HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

    $client = new Client('Your API Key', 'Your API Secret', $HttpAdapter);
    $client->setTokenStore(new SessionStore());

    if(null !== $client->getAccessToken()) {
        //user is still authenticated
    } else {
        //force user to authenticate by providing the clicking authorization url
        echo $client->getAuthorizationUrl();
    }

