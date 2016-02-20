CHANGELOG
---------
* 0.6.1 (11-14-2014)
  * Fix getSubdefs method for record.
    
* 0.6.0 (10-31-2014)

  * Fix thumbnail property for story was not properly populated.
  * Add getStatus method for stories.
  * Add getCaption method for stories.

* 0.5.0 (10-08-2014)

  * Add proxies for entities, removing dependencies to entity manager.
  * Add annotations for mapping entities properties to API response.
  * Add User entity.
  * Add support for localized labels.
  * Add possibility to override can_cache strategy, re-validation strategy, key provider and
    cache adapter for cache plugin.
  * Compatible with Phraseanet API version 1.4.
  * BC Break : Add consistency in entities API. (see UPGRADE.md)
  * BC Break : Renamed some services in Silex ServiceProvider. (see UPGRADE.md)
  * Add possibility to fetch extended API response.
  * Add support for /me route.

* 0.4.5 (05-05-2014)

  * Implements feeds restriction on aggregated feed entries.

* 0.4.4 (03-25-2014)

  * Add possibility to fetch a story caption.

* 0.4.3 (03-24-2014)

  * Remove final constructor in entities to allow overloading.

* 0.4.2 (02-19-2014)

  * Avoid player crashs because of HTTP failure.

* 0.4.1 (01-31-2014)

  * Add post parameters to profiler.

* 0.4.0 (06-21-2013)

  * Add SDK Loader to upload files to Phraseanet.

* 0.3.0 (06-10-2013)

  * Add PhraseanetSDK\Application ; BC Break, PhraseanetSDK\Client does not
    exist anymore.
  * Add cache support.
  * Add simpler log support.
  * Add support for PhraseanetSDK profiling through silex web profiler.
  * Add requests recorder.
  * Add support for monitoring routes.

* 0.2.2 (03-18-2013)

  * Add a documentation recipe to test the connection to Phraseanet
  * SDK now depends on doctrine/collections instead of doctrine/common
  * Add Entity\Record::getSubdefsByDevicesAndMimeTypes method

* 0.2.1 (03-11-2013)

  * SDK now depends on Guzzle 3.x (instead of Guzzle >= 2.7 previously)

* 0.2.0 (03-11-2013)

  * New feature : Add ability to fetch stories
  * Compatible with Phraseanet API version 1.3

* 0.1.0 (03-11-2013)

  * First stable version, compatible with Phraseanet API version 1.2
