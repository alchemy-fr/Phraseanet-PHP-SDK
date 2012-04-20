<?php

namespace Test\Tools\Entity;

use PhraseanetSDK\Tools\Entity\Factory as EntityFactory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @dataProvider classProvider
   */
  public function testFactory($type)
  {
    $this->assertTrue(is_object(EntityFactory::factory($type)));
  }

  /**
   * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
   */
  public function testExceptionFactory()
  {
    EntityFactory::factory('unknow_class_type');
  }

  public function classProvider()
  {
    return array(
        array('entry'),
        array('feed'),
        array('item'),
        array('metadatas'),
        array('permalink'),
        array('record'),
        array('subdef'),
        array('technical'),
        array('entries'),
        array('technical_informations'),
        array('thumbnail'),
        array('items')
    );
  }

}

