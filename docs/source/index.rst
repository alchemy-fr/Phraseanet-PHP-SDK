Documentation
=============

Introduction
------------

Phraseanet PHP SDK is an object oriented PHP wrapper that allows you to access
the Phraseanet API from your website.

Design
------

This library has been strongly inspired by ORM's and sits on top of the
Phraseanet API.

It comes with a set of entities which store the datas and represent the logical
structure of the API then we have a set of repositories objects that allow you
to map
the API response to entities object.


This is a **read only** library thus you can only fetch object from the API.

Installation
------------

We rely on `composer <http://getcomposer.org/>`_ to use this library. If you do
no still use composer for your project, you can start with this
``composer.json`` at the root of your project:

.. code-block:: bash

    {
        "require": {
            "phraseanet/php-sdk": "dev-master"
        }
    }

Install composer :

.. code-block:: bash

    # Install composer
    curl -s http://getcomposer.org/installer | php
    # Upgrade your install
    php composer.phar install

You now just have to autoload the library to use it :

.. code-block:: php

    <?php
    require 'vendor/autoload.php';

This is a very short intro to composer.
If you ever experience an issue or want to know more about composer,
you will find help on their  website
`http://getcomposer.org/ <http://getcomposer.org/>`_.

Phraseanet SDK client
---------------------

The Client object is the heart of the SDK, it performs HTTP requests and handles
authentication flow as well.

**Get a Phraseanet client**

To ease the use of the configuration and the flexibility of the
Phraseanet Client, it relies on a Guzzle Http Client object
`http://guzzlephp.org/index.html <http://guzzlephp.org/index.html>`_ which is
highly customizable.

.. code-block:: php

    <?php

    use PhraseanetSDK\Client;
    use PhraseanetSDK\HttpAdapter\Guzzle as HttpAdapter;

    $httpClient = new  Guzzle\Http\Client();
    $httpClient->setBaseUrl('http://your.instance-api.url/');

    $HttpAdapter = new HttpAdapter($httpClient);

    $client = new Client('Your API Key', 'Your API Secret', $HttpAdapter);

.. note::
    See Guzzle documention `http://guzzlephp.org/docs.html <http://guzzlephp.org/docs.html>`_
    to customize the http client. For example adding some caching rules or one of
    the many plugins provided by Guzzle.

Authentication
--------------

**All requests performed on Phraseanet API are authenticated requests.**

Phraseanet API implements OAuth2.0 `http://oauth.net/2/ <http://oauth.net/2/>`_
authentication flow, this means you MUST provide an oAuth ACCESS_TOKEN to request
the API otherwise you will get a 401 Unauthorized response.

.. code-block:: php

    <?php

    $client->setAccessToken('YOUR_ACCESS_TOKEN');

**How to get a token from the API ?**

Phraseanet API supports only one grant type the 'Token Grant Type'.

With this grant type you redirect the user to an authorization page on
Phraseanet, and your script is called back once the end-user authorized your API
key to access the Phraseanet service on its behalf.


**Authorization page**

    .. code-block:: php

        <?php

        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION, array('redirect_uri' => 'YOUR_REDIRECT_URI'));

        //output the authentication url to the end user
        echo $client->getAuthorizationUrl();

.. note::
    In case your authorization page is the same that your callback page
    //modified the flow

**Callback page**

    .. code-block:: php

        <?php
        use Symfony\Component\HttpFoundation\Request;

        $request = Request::createFromGlobals();

        //retrieve the access token from current request
        try {
            $client->retrieveAccessToken($request);
        } catch (AuthenticationException $e) {
            //Something went wrong during the authentication flow
        } catch (RuntimeException $e) {
            //Something went wrong for obscur reasons during the retrieval of the token
        }

.. note::
    ACCESS_TOKEN does not expire.
    So once you have an ACCESS_TOKEN associated to your current user,
    you can manage user's token with your own storage system on top of the
    library or you can just extends the PhraseanetSDK\Client object an override
    the *getAccessToken* and *setAccessToken* method. See :doc:`session storage
    example in recipe <recipes>`.

Entity Manager
--------------

**The entity manager**

The EntityManager class is a central access point to the SDK functionality.

.. code-block:: php

        <?php
        use PhraseanetSDK\Tools\Entity\Manager;

        $entityManager = new Manager($client);

SDK entities
------------

Entities are objects with identity. Their identity has a conceptual meaning
inside The phraseanet API Domain.

- Basket
- BasketElement
- BasketValidationChoice
- BasketValidationParticipant
- Databox
- DataboxCollection
- DataboxDocumentStructure
- DataboxStatus
- Feed
- FeedEntry
- FeedEntryItem
- Metadatas
- Permalink
- Quarantine
- QuarantineSession
- Query
- QuerySuggestion
- Record
- RecordCaption
- Subdef
- Technical

Since the SDK is a read only library, you will never have the need to create a
new entity object but just getting them trought the API.

However for getting a new entity object use the Entity Manager.

.. code-block:: php

        <?php
        //$em is an Entity Manager instance
        $recordEntity = $em->getEntity('Record');
        $basketEntity = $em->getEntity('Basket');

SDK repositories
----------------

Repositories are objects which provides many ways to retrieve entities of the
specified type trought the API.

- Basket
- BasketElement
- Caption
- Databox
- DataboxCollection
- DataboxDocumentStructure
- DataboxStatus
- Entry
- Feed
- Metadatas
- Quarantine
- Record
- RecordStatus
- Subdef

To get a repository instance use the Entity Manager

.. code-block:: php

        <?php
        //$em is an Entity Manager instance
        $recordRepository = $em->getRepository('Record');
        $basketRepository = $em->getRepository('Basket');

Fetching datas from API
-----------------------

Now it is very easy to fetch datas from API.
You have to get the repository type that relies on the data type you want.

Let's say I want to fetch some records.
As I want Records, I need the Record Repository.

If we look inside the Record Repository, we can see that this Repository
provides three differents methods to retrieves Records.

Some of repository methods will fetch one entity some others will fetch a
collection of entities.

**Fetching one element**

.. code-block:: php

        <?php
        //$em is an Entity Manager instance
        $recordRepository = $em->getRepository('Record');

        $databoxId = 1;
        $recordId = 234;
        //Fetch one record identified by its databox id ans its own id
        //Return a Record Entity object
        $record = recordRepository->findById($databoxId, $recordId);

**Fetching a collection of elements**

.. code-block:: php

        <?php
        //$em is an Entity Manager instance
        $recordRepository = $em->getRepository('Record');

        $offsetStart = 1;
        $perPage = 20;
        //Fetch 20 records
        //Return a Doctrine Array Collection object
        $records = recordRepository->find($offsetStart, $perPage);

        foreach($records as $record) {
            echo $record->getTitle() . "\n";
        }

Lazy Loading
------------

Whenever you have an entity instance at hand, you can traverse and use any
associations of that entity to retrieve the associated objects.

.. code-block:: php

        <?php
        //Fetch one record
        $record = $recordRepository->findById(1, 87);

        //Get the associated status
        //This method will execute a request throught the api
        //to load the record status
        $status = $record->getStatus();


.. note::
    Try to avoid lazy loading in a loop unless you have some cache implementation to
    reduce the number of requests.

Recipes
-------

You'll find usefull recipes in our :doc:`recipes`

.. toctree::
   :maxdepth: 4

   recipes

Handling Exceptions
-------------------

The PHP SDK throws 3 different types of exception :

- ``PhraseanetSDK\Exception\Runtime`` is thrown when something went wrong most of the time you can get more informations by getting the previous exception.
- ``PhraseanetSDK\Exception\UnauthorizedException`` which is thrown when request is not authenticated
- ``PhraseanetSDK\Exception\NotFoundException`` which is thrown when the requested ressource can not be found

All these Exception implements ``\PhraseanetSDK\Exception\ExceptionInterface`` so you can catch
any of these exceptions by catching this exception interface.

Report a bug
------------

If you experience an issue, please report it in our
`issue tracker <https://github.com/alchemy-fr/Phraseanet-PHP-SDK/issues>`_. Before
reporting an issue, please be sure that it is not already reported by browsing
open issues.

When reporting, please give us information to reproduce it by giving your
platform (Linux / MacOS / Windows) and its version, the version of PHP you use
(the output of ``php --version``)

Ask for a feature
-----------------

We would be glad you ask for a feature ! Feel free to add a feature request in
the `issues manager <https://github.com/alchemy-fr/Phraseanet-PHP-SDK/issues>`_ on GitHub !

Contribute
----------

You find a bug and resolved it ? You added a feature and want to share ? You
found a typo in this doc and fixed it ? Feel free to send a
`Pull Request <http://help.github.com/send-pull-requests/>`_ on GitHub, we will
be glad to merge your code.

Run tests
---------

Phraseanet-PHP-SDK relies on `PHPUnit <http://www.phpunit.de/manual/current/en/>`_ for
unit tests. To run tests on your system, ensure you have PHPUnit installed,
and, at the root of the project, execute it :

.. code-block:: bash

    phpunit

About
-----

Phraseanet-PHP-SDK has been written by the Alchemy Dev Team
for `Phraseanet <https://github.com/alchemy-fr/Phraseanet>`_, our DAM software.
Try it, it's awesome !

License
-------

Phraseanet-PHP-SDK is licensed under the `MIT License <http://opensource.org/licenses/MIT>`_