Documentation
=============


This documentation is out of date, please refer to the repository
Phraseanet-PHP-SDK <https://github.com/alchemy-fr/Phraseanet-PHP-SDK



Introduction
------------

Phraseanet PHP SDK is an object oriented PHP library that gives you access to
the your Phraseanet ressources from your PHP application/website.

Design
------

This library has been strongly inspired by Doctrine ORM design and sits on top
of `Phraseanet API<https://docs.phraseanet.com/Devel>`.

The aim is to let you build your application/mashup as if you were connected to
a database.

This library provide a set of entities mapped to the logical structure of the
API and a set of related repositories to fetch this entities in various ways.

This is a **read only** library ; current version only allow read from the API.

Installation
------------

We rely on `composer <http://getcomposer.org/>`_ to use this library. If you do
no still use composer for your project, you can start with this
``composer.json`` at the root of your project:

.. code-block:: json

    {
        "require": {
            "phraseanet/php-sdk": "~0.2"
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

The SDK
-------

To use the SDK, you will need an **Entity Manager**. This EntityManager,
provides methods to access repositories, delegating all work to an HTTP client.

Most of the time, you'll only have to set your credentials to this client. If
you want to customize it (use a configuration for your credentials, add
Memcached or Redis cache...) see the dedicated section.

Phraseanet SDK client
+++++++++++++++++++++

The Client object is the heart of the SDK, it performs HTTP requests and handles
authentication flow as well.

**Get a Phraseanet client**

For the following example we will use Guzzle Http Client Adapter which use
Guzzle `http://guzzlephp.org/index.html <http://guzzlephp.org/index.html>`_,
highly customizable library.

.. code-block:: php

    <?php
    use PhraseanetSDK\Client;
    use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;

    $HttpAdapter = GuzzleAdapter::create();
    $HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

    $client = new Client('Your API Key', 'Your API Secret', $HttpAdapter);

.. note::
    You have to create an application in Phraseanet to get credentials. This
    application will be created in "Account" => "Applications".

Authentication
^^^^^^^^^^^^^^

**All requests performed on Phraseanet API are authenticated requests.**

Phraseanet API implements OAuth2.0 `http://oauth.net/2/ <http://oauth.net/2/>`_
authentication flow, this means you MUST provide an oAuth ACCESS_TOKEN to request
the API otherwise you will get a ``PhraseanetSDK\Exception\UnauthorizedException``.

There are two ways to use the SDK in your application. Either you will use the
same token for every request, either you will use a custom token using the
whole oAuth2 authentication flow.

If you want to use this second way, please read the dedicated article in the
:doc:`recipes <recipes>`.

Use developer Token
~~~~~~~~~~~~~~~~~~~

.. code-block:: php

    <?php
    $client->setAccessToken('YOUR_ACCESS_TOKEN');

Full authentication flow
~~~~~~~~~~~~~~~~~~~~~~~~

If you do not want to use the developer token and prefer the oAuth2 full
authentication flow, read the dedicated doc in the :doc:`recipes <recipes>`.

Entity Manager
++++++++++++++

Once you have a client, you'll have access to the ``EntityManager`` ; it is the
central access point to Phraseanet ressources.

.. code-block:: php

    <?php
    use PhraseanetSDK\EntityManager;

    $entityManager = new EntityManager($client);

    // retrieve a collection of all feeds available
    $feeds = $entityManager->getRepository('Feed')->findAll();

Repositories
^^^^^^^^^^^^

Repositories are access point to entities.
To get a repository instance use the Entity Manager

.. code-block:: php

    <?php
    // $em is an Entity Manager instance
    $recordRepository = $em->getRepository('Record');
    // return the 20 latest records
    $recordRepository->find(0, 20);

    $basketRepository = $em->getRepository('Basket');
    // return all the active baskets of the user
    $basketRepository->findAll();

Repositories methods depend of the repository. Here is the list of all available
repositories and a link to the related API documentation.

- `Basket repository <_static/API/PhraseanetSDK/Repository/Basket.html>`_
- `BasketElement repository <_static/API/PhraseanetSDK/Repository/BasketElement.html>`_
- `Caption repository <_static/API/PhraseanetSDK/Repository/Caption.html>`_
- `Databox repository <_static/API/PhraseanetSDK/Repository/Databox.html>`_
- `DataboxCollection repository <_static/API/PhraseanetSDK/Repository/DataboxCollection.html>`_
- `DataboxDocumentStructure repository <_static/API/PhraseanetSDK/Repository/DataboxDocumentStructure.html>`_
- `DataboxStatus repository <_static/API/PhraseanetSDK/Repository/DataboxStatus.html>`_
- `Entry repository <_static/API/PhraseanetSDK/Repository/Entry.html>`_
- `Feed repository <_static/API/PhraseanetSDK/Repository/Feed.html>`_
- `Metadatas repository <_static/API/PhraseanetSDK/Repository/Metadatas.html>`_
- `Quarantine repository <_static/API/PhraseanetSDK/Repository/Quarantine.html>`_
- `Record repository <_static/API/PhraseanetSDK/Repository/Record.html>`_
- `RecordStatus repository <_static/API/PhraseanetSDK/Repository/RecordStatus.html>`_
- `Subdef repository <_static/API/PhraseanetSDK/Repository/Subdef.html>`_

Entities
^^^^^^^^

Entities are ressources with identity. Their identity has a conceptual meaning
inside the Phraseanet API Domain.

Since the SDK is a read only library, you will never have the need to create a
new entity object but just getting them trought the API.

.. code-block:: php

    <?php
    // $em is an Entity Manager instance
    $recordRepository = $em->getRepository('Record');

    // return the 20 latest records as record entities
    $records = $recordRepository->find(0, 20);

    foreach($records as $record) {
        // $record is an entity

        // return the title of the record
        $record->getTitle();

        // return a Subdef entity corresponding to the thumbnail
        $thumbnail = $record->getThumbnail();

        if($thumbnail) {
            $url = $thumbnail->getPermalink()->getUrl();
        }
    }

Here is a complete list of all entities provided by the API and a link to their
API doc.

- `Basket <_static/API/PhraseanetSDK/Entity/Basket.html>`_
- `BasketElement <_static/API/PhraseanetSDK/Entity/BasketElement.html>`_
- `BasketValidationChoice <_static/API/PhraseanetSDK/Entity/BasketValidationChoice.html>`_
- `BasketValidationParticipant <_static/API/PhraseanetSDK/Entity/BasketValidationParticipant.html>`_
- `Databox <_static/API/PhraseanetSDK/Entity/Databox.html>`_
- `DataboxCollection <_static/API/PhraseanetSDK/Entity/DataboxCollection.html>`_
- `DataboxDocumentStructure <_static/API/PhraseanetSDK/Entity/DataboxDocumentStructure.html>`_
- `DataboxStatus <_static/API/PhraseanetSDK/Entity/DataboxStatus.html>`_
- `Feed <_static/API/PhraseanetSDK/Entity/Feed.html>`_
- `FeedEntry <_static/API/PhraseanetSDK/Entity/FeedEntry.html>`_
- `FeedEntryItem <_static/API/PhraseanetSDK/Entity/FeedEntryItem.html>`_
- `Metadatas <_static/API/PhraseanetSDK/Entity/Metadatas.html>`_
- `Permalink <_static/API/PhraseanetSDK/Entity/Permalink.html>`_
- `Quarantine <_static/API/PhraseanetSDK/Entity/Quarantine.html>`_
- `QuarantineSession <_static/API/PhraseanetSDK/Entity/QuarantineSession.html>`_
- `Query <_static/API/PhraseanetSDK/Entity/BasketValidationParticipant.html>`_
- `QuerySuggestion <_static/API/PhraseanetSDK/Entity/QuerySuggestion.html>`_
- `Record <_static/API/PhraseanetSDK/Entity/Record.html>`_
- `RecordCaption <_static/API/PhraseanetSDK/Entity/RecordCaption.html>`_
- `Subdef <_static/API/PhraseanetSDK/Entity/Subdef.html>`_
- `Technical <_static/API/PhraseanetSDK/Entity/Technical.html>`_

Fetching datas from API
+++++++++++++++++++++++

Now you have your entity manager, it is very easy to fetch datas.
You have to get the repository type that relies on the data type you want.

Let's say you want records, then you need the Record Repository.

Look at the Record Repository API, and see that there are three ways to
retrieve records :

- findById
- find
- search

Some of the repository methods will fetch one entity and some others will
retrieve a collection of entities.

**Fetching one element**

.. code-block:: php

    <?php
    // $em is an Entity Manager instance
    $recordRepository = $em->getRepository('Record');

    $databoxId = 1;
    $recordId = 234;

    // Fetch one record identified by its databox id ans its own id
    // Return a Record Entity object
    $record = recordRepository->findById($databoxId, $recordId);

**Fetching a collection of elements**

.. code-block:: php

    <?php
    // $em is an Entity Manager instance
    $recordRepository = $em->getRepository('Record');

    $offsetStart = 1;
    $perPage = 20;

    // Fetch 20 records
    // Return a Doctrine Array Collection object
    $records = recordRepository->find($offsetStart, $perPage);

    foreach($records as $record) {
        echo $record->getTitle() . "\n";
    }

Lazy Loading
++++++++++++

Whenever you have an entity instance at hand, you can traverse and use any
associations of that entity to retrieve the associated objects.

.. code-block:: php

    <?php
    // Fetch one record
    $record = $recordRepository->findById(1, 87);

    // Will execute a request to the api to load the record status
    $status = $record->getStatus();

    // performs another request to get subdefs
    foreach($record->getSubdefs() as $subdef) {
        echo "Sub definition " . $subdef->getName() . " has URL " . $subdef->getPermalink()->getUrl() . "\n";
    }

.. note::
    Try to avoid lazy loading in a loop unless you have some cache
    implementation to reduce the number of requests.

Recipes
+++++++

You'll find usefull recipes in our :doc:`recipes`

.. toctree::
   :maxdepth: 4

   recipes

Handling Exceptions
+++++++++++++++++++

The PHP SDK throws 3 different types of exception :

- ``PhraseanetSDK\Exception\Runtime`` is thrown when something went wrong most
  of the time you can get more informations by getting the previous exception.
- ``PhraseanetSDK\Exception\UnauthorizedException`` which is thrown when request
  is not authenticated
- ``PhraseanetSDK\Exception\NotFoundException`` which is thrown when the
  requested ressource can not be found

All these Exception implements ``\PhraseanetSDK\Exception\ExceptionInterface`` so you can catch
any of these exceptions by catching this exception interface.

Report a bug
------------

If you experience an issue, please report it in our
`issue tracker <https://github.com/alchemy-fr/Phraseanet-PHP-SDK/issues>`_.
Before reporting an issue, please be sure that it is not already reported by
browsing open issues.

When reporting, please give us information to reproduce it by giving your
platform (Linux / MacOS / Windows) and its version, the version of PHP you use
(the output of ``php --version``)

Ask for a feature
-----------------

We would be glad you ask for a feature ! Feel free to add a feature request in
the `issues manager <https://github.com/alchemy-fr/Phraseanet-PHP-SDK/issues>`_
on GitHub !

Contribute
----------

You find a bug and resolved it ? You added a feature and want to share ? You
found a typo in this doc and fixed it ? Feel free to send a
`Pull Request <http://help.github.com/send-pull-requests/>`_ on GitHub, we will
be glad to merge your code.

Run tests
---------

Phraseanet-PHP-SDK relies on `PHPUnit <http://www.phpunit.de/manual/current/en/>`_
for unit tests. To run tests on your system, ensure you have PHPUnit installed,
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

Phraseanet-PHP-SDK is licensed under the
`MIT License <http://opensource.org/licenses/MIT>`_