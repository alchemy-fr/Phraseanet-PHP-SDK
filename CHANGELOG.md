# CHANGELOG

## [1.0.0] - 2021-01-18
### Breaking
  - The sdk now uses "guzzlehttp/guzzle": "7.1.1"
  - cache, log, record, play, profiling, ... which did rely on guzzle-3 plugings are deleted

## Unreleased
### Changed
  - CHANGELOG.md now uses keepachangelog.com format
  - Minor documentation improvements
  
## [0.9.0] - 2016-03-25
### Breaking
- `AbstractRepository` class has moved one namespace up. It's new FQCN is `PhraseanetSDK\AbstractRepository`.
- Support for calling V2 (and newer) routes has been added

### Removed
- Removed inlined CSS (excepted for tree view) and JS from profiler templates: allows to keep in line with global profiler styling
- Removed support for PHP 5.4
  
## [0.8.0] - 2015-10-30
### Removed
  - Removed use of annotations to configure entity hydration, replaced by manual JSON hydration.
  
## [0.7.2] - 2015-09-04
### Added
  - Support for facet entities returned by newer Phraseanet versions (>= 4.0)
  
## [0.7.1] - 2015-08-26
### Fixed
  - Improved documentation blocks in source
  
## [0.7.0] - 2015-07-29
### Added
  - Adds support for user administration routes (requires an app token created with an admin account):
    - `Repository\User::requestCollections(array $collections)`
    - `Repository\User::requestPasswordReset(string $emailAddress)`
    - `Repository\User::resetPassword(string $token, string $password)`
    - `Repository\User::createUser(User $user, string $password, array $collections = null)`
  - Adds support for change password route (only valid for current user)
    - `Repository\User::updatePassword(string $currentPassword, string $newPassword)`
    - `Repository\User::deleteAccount()`
    - `Repository\User::unlockAccount(string $token)`
  - Adds `databoxId` property to `DataboxCollection` entities
  - Adds `baseId` property to `Record` entities
  - Adds `collectionRights` and `collectionDemands` properties to `User` entities
  - Adds `find` method to `DataboxCollection` repository
  

## [0.6.1] - 2014-11-14
### Fixed
  - Fix getSubdefs method for record.
    
## [0.6.0] - 2014-10-31
### Fixed
  - Fix thumbnail property for story was not properly populated.
  
### Added
  - Add getStatus method for stories.
  - Add getCaption method for stories.

## [0.5.0] - 2014-08-10
### Changed
  - BC Break : Add consistency in entities API. (see UPGRADE.md)
  - BC Break : Renamed some services in Silex ServiceProvider. (see UPGRADE.md)

### Added
  - Add proxies for entities, removing dependencies to entity manager.
  - Add annotations for mapping entities properties to API response.
  - Add User entity.
  - Add support for localized labels.
  - Add possibility to override can_cache strategy, re-validation strategy, key provider and
    cache adapter for cache plugin.
  - Compatible with Phraseanet API version 1.4.
  - Add possibility to fetch extended API response.
  - Add support for /me route.

## [0.4.5] - 2014-05-05
### Added
  - Implements feeds restriction on aggregated feed entries.

## [0.4.4] - 2014-03-25
### Added
  - Add possibility to fetch a story caption.

## [0.4.3] - 2014-03-24
### Added
  - Remove final constructor in entities to allow overloading.

## [0.4.2] - 2014-02-19
### Fixed
  - Avoid player crashs because of HTTP failure.

## [0.4.1] - 2014-01-31
### Added
  - Add post parameters to profiler.

## [0.4.0] - 2013-21-06
### Added
  - Add SDK Loader to upload files to Phraseanet.

## [0.3.0] - 2013-10-06
### Added
  - Add PhraseanetSDK\Application ; BC Break, PhraseanetSDK\Client does not
    exist anymore.
  - Add cache support.
  - Add simpler log support.
  - Add support for PhraseanetSDK profiling through silex web profiler.
  - Add requests recorder.
  - Add support for monitoring routes.

## [0.2.2] - 2013-03-18
### Added
  - Add a documentation recipe to test the connection to Phraseanet
  - SDK now depends on doctrine/collections instead of doctrine/common
  - Add Entity\Record::getSubdefsByDevicesAndMimeTypes method

## [0.2.1] - 2013-03-11
### Added
  - SDK now depends on Guzzle 3.x (instead of Guzzle >= 2.7 previously)

## [0.2.0] - 2013-03-11
### Added
  - New feature : Add ability to fetch stories
  - Compatible with Phraseanet API version 1.3

## [0.1.0] - 2013-03-11
### Addded
  - First stable version, compatible with Phraseanet API version 1.2
