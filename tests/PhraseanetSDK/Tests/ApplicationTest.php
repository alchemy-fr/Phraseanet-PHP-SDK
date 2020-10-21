<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Application;
use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Exception\InvalidArgumentException;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $config = array(
            'client-id' => 'EPWqblt',
            'secret' => 'R74hCnX8',
            'url' => 'http://phraseanet.com',
        );
        $application = Application::create($config);

        $this->assertInstanceOf(Application::class, $application);
    }

    /**
     * @dataProvider provideVariousInvalidConfigurations
     *
     */
    public function testCreateFailure($config)
    {
        $this->expectException(InvalidArgumentException::class);
        Application::create($config);
    }

    public function provideVariousInvalidConfigurations()
    {
        return array(
            array(
                array(
                    'secret' => 'cdyfdyGu',
                    'url' => 'http://phraseanet.com/api/v1',
                ),
            ),
            array(
                array(
                    'client-id' => 'SF8HsrT',
                    'url' => 'http://phraseanet.com/api/v1',
                ),
            ),
            array(
                array(
                    'client-id' => 'X7hOdxA',
                    'secret' => 'XzVw6cIQ',
                ),
            ),
        );
    }

    public function testOauth2ConnectorAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder(GuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var GuzzleAdapter $adapter */
        $application = new Application($adapter, '12345', '54321');
        $connector = $application->getOauth2Connector();

        $this->assertInstanceOf('PhraseanetSDK\OAuth2Connector', $connector);

        $this->assertSame($connector, $application->getOauth2Connector());
    }

    public function testEntityManagersAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder(GuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token1 = 'vpJPb2Wh';
        $token2 = 'E8xuvVVq';

        /** @var GuzzleAdapter $adapter */
        $application = new Application($adapter, 'mQ3ol1F', 'FgwhjuQW');

        $em1 = $application->getEntityManager($token1);
        $em2 = $application->getEntityManager($token2);

        $this->assertInstanceOf('PhraseanetSDK\EntityManager', $em1);
        $this->assertInstanceOf('PhraseanetSDK\EntityManager', $em2);

        $this->assertNotSame($em2, $em1);

        $this->assertSame($em1, $application->getEntityManager($token1));
        $this->assertSame($em2, $application->getEntityManager($token2));
    }

    public function testLoadersAreAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder(GuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token1 = 'eijbOAMd';
        $token2 = 'loXZ8qa8';

        /** @var GuzzleAdapter $adapter */
        $application = new Application($adapter, 'OePvPlq8', 'oC4h3QYp');

        $loader1 = $application->getUploader($token1);
        $loader2 = $application->getUploader($token2);

        $this->assertInstanceOf('PhraseanetSDK\Uploader', $loader1);
        $this->assertInstanceOf('PhraseanetSDK\Uploader', $loader2);

        $this->assertNotSame($loader2, $loader1);

        $this->assertSame($loader1, $application->getUploader($token1));
        $this->assertSame($loader2, $application->getUploader($token2));
    }

    public function testMonitorAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder(GuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token1 = 'rfM7YHPq';
        $token2 = '3PubP90u';

        /** @var GuzzleAdapter $adapter */
        $application = new Application($adapter, 'DMiykp0k', 'sshavAJm');

        $mon1 = $application->getMonitor($token1);
        $mon2 = $application->getMonitor($token2);

        $this->assertInstanceOf('PhraseanetSDK\Monitor', $mon1);
        $this->assertInstanceOf('PhraseanetSDK\Monitor', $mon2);

        $this->assertNotSame($mon1, $mon2);

        $this->assertSame($mon1, $application->getMonitor($token1));
        $this->assertSame($mon2, $application->getMonitor($token2));
    }

    /**
     * @dataProvider provideInvalidTokens
     */
    public function testEntityManagersWithoutTokenThrowsException($token)
    {
        $this->expectException(InvalidArgumentException::class);

        $adapter = $this->getMockBuilder(GuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var GuzzleAdapter $adapter */
        $application = new Application($adapter, 'JLZqzMDG', '36QCU07C');

        $application->getEntityManager($token);
    }

    public function provideInvalidTokens()
    {
        return array(
            array(null),
            array(''),
        );
    }
}
