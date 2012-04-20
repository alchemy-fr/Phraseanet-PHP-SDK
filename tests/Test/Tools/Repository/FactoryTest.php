<?php

namespace Test\Tools\Repository;

use PhraseanetSDK\Tools\Repository\Factory as RepoFactory;
use PhraseanetSDK\Tools\Repository\RepositoryAbstract;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider classprovider
     */
    public function testFactory($type)
    {
        $em = $this->getMock(
                'PhraseanetSDK\\Tools\\Entity\\Manager'
                , array()
                , array()
                , ''
                , false
        );
        
        $repo = RepoFactory::factory($type, $em);
        
        $this->assertTrue($repo instanceof RepositoryAbstract);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testExceptionFactory()
    {
         $em = $this->getMock(
                'PhraseanetSDK\\Tools\\Entity\\Manager'
                , array()
                , array()
                , ''
                , false
        );
         
        RepoFactory::factory('unknow_class_type', $em);
    }

    public function classProvider()
    {
        return array(
            array('feed')
        );
    }

}

