<?php

namespace Test\Tools\Repository;

use PhraseanetSDK\Tools\Repository\Factory as RepoFactory;
use PhraseanetSDK\Repository\AbstractRepository;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider classprovider
     */
    public function testFactory($type)
    {
        $em = $this->getMock(
            'PhraseanetSDK\\EntityManager'
            , array()
            , array()
            , ''
            , false
        );

        $repo = RepoFactory::build($type, $em);

        $this->assertTrue($repo instanceof AbstractRepository);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testExceptionFactory()
    {
        $em = $this->getMock(
            'PhraseanetSDK\\EntityManager'
            , array()
            , array()
            , ''
            , false
        );

        RepoFactory::build('unknow_class_type', $em);
    }

    public function classProvider()
    {
        return array(
            array('feed')
        );
    }
}
