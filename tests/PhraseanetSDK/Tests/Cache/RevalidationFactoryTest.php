<?php

namespace PhraseanetSDK\Tests\Cache;

use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Exception\RuntimeException;

class RevalidationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideRevalidationNames
     */
    public function testRevalidationCreation($type, $instanceOf)
    {
        $factory = new RevalidationFactory();
        $this->assertInstanceOf($instanceOf, $factory->create($type));
    }

    public function provideRevalidationNames()
    {
        return array(
            array('SKIP', 'Guzzle\Plugin\Cache\SkipRevalidation'),
            array('skip', 'Guzzle\Plugin\Cache\SkipRevalidation'),
            array('deny', 'Guzzle\Plugin\Cache\DenyRevalidation'),
            array('DENY', 'Guzzle\Plugin\Cache\DenyRevalidation'),
        );
    }

    public function testInvalidNameThrowsAnException()
    {
        $this->expectException(RuntimeException::class);
        $factory = new RevalidationFactory();
        $factory->create('always');
    }
}
