<?php

namespace Test\Tools\Repository;

use Alchemy\Sdk\Tools\Repository\Factory as RepoFactory;
use Alchemy\Sdk\Tools\Repository\RepositoryAbstract;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider classprovider
     */
    public function testFactory($type)
    {
        $em = $this->getMock(
                'Alchemy\\Sdk\\Tools\\Entity\\Manager'
                , array()
                , array()
                , ''
                , false
        );
        
        $repo = RepoFactory::factory($type, $em);
        
        $this->assertTrue($repo instanceof RepositoryAbstract);
    }

    /**
     * @expectedException Alchemy\Sdk\Exception\InvalidArgumentException
     */
    public function testExceptionFactory()
    {
         $em = $this->getMock(
                'Alchemy\\Sdk\\Tools\\Entity\\Manager'
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

