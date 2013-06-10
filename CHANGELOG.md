CHANGELOG
---------

* 0.3.0 (xx-xx-2013)

  * Add PhraseanetSDK\Application ; BC Break, PhraseanetSDK\Client does not
    exist anymore.
  * Add cache support.
  * Add simpler log support.
  * Add support for PhraseanetSDK profiling through silex web profiler.
  * Add requests recorder.

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
