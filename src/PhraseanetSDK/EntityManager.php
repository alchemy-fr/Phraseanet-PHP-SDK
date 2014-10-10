<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use ProxyManager\Configuration as ProxyConfig;
use ProxyManager\Factory\LazyLoadingGhostFactory;
use Psr\Log\LoggerInterface;

class EntityManager
{
    private $adapter;
    private $annotationReader;
    private $proxyFactory;
    private $repositories = array();
    private $virtualProxies = array();

    /**
     * @param APIGuzzleAdapter $adapter
     */
    public function __construct(APIGuzzleAdapter $adapter, array $options = array())
    {
        $this->adapter = $adapter;
        self::registerAnnotations();

        $debug = isset($options['debug']) && !!$options['debug'];
        $annotationsPath = isset($options['annotation.path']) ? $options['annotation.path'] : __DIR__.'/../../cache/annotations';
        $proxyPath = isset($options['proxy.path']) ? $options['proxy.path'] : __DIR__ . '/../../proxies';

        $logger = (isset($options['logger']) && $options['logger'] instanceof LoggerInterface) ? $options['logger'] : null;

        if (!$logger && $debug) {
            $logger = new Logger('phraseanet-php-sdk');
            $logger->pushHandler(new StreamHandler(__DIR__ . '/../../log/sdk.log'));
        }

        $this->annotationReader = new FileCacheReader(
            new AnnotationReader(),
            $annotationsPath,
            $debug
        );

        $config = new ProxyConfig();
        $config->setProxiesTargetDir($proxyPath);

        spl_autoload_register($config->getProxyAutoloader());

        $this->proxyFactory = new LazyLoadingGhostFactory($config);

        $this->logger = $logger;
    }

    /**
     * @return Logger|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return FileCacheReader
     */
    public function getAnnotationReader()
    {
        return $this->annotationReader;
    }

    /**
     * @return LazyLoadingGhostFactory
     */
    public function getProxyFactory()
    {
        return $this->proxyFactory;
    }

    /**
     * Return the client attached to this entity manager
     *
     * @return APIGuzzleAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get a repository by its name
     *
     * @param  string                                        $name
     * @return \PhraseanetSDK\Repository\RepositoryInterface
     */
    public function getRepository($name)
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        $className = ucfirst($name);
        $objectName = sprintf('\\PhraseanetSDK\\Repository\\%s', $className);

        if (!class_exists($objectName)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Class %s does not exists', $objectName)
            );
        }

        return $this->repositories[$name] = new $objectName($this);
    }

    /**
     * Return virtual proxy for given entity
     *
     * @param $name
     *
     * @return mixed
     */
    public function getVirtualProxy($name)
    {
        if (isset($this->virtualProxies[$name])) {
            return $this->virtualProxies[$name];
        }

        $className = ucfirst($name);
        $objectName = sprintf('\\PhraseanetSDK\\VirtualProxy\\%s', $className);

        if (!class_exists($objectName)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Class %s does not exists', $objectName)
            );
        }

        return $this->virtualProxies[$name] = new $objectName($this);
    }

    /**
     * Register entities annotations
     */
    private static function registerAnnotations()
    {
        AnnotationRegistry::registerFile(__DIR__.'/Annotation/ApiField.php');
        AnnotationRegistry::registerFile(__DIR__.'/Annotation/ApiObject.php');
        AnnotationRegistry::registerFile(__DIR__.'/Annotation/ApiRelation.php');
    }
}
