<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Player;

class PlayerTest extends \PHPUnit_Framework_TestCase
{
    public function testPlay()
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\APIGuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();
        $storage = $this->getMock('PhraseanetSDK\Recorder\Storage\StorageInterface');

        $serializedRequest = array(
            'query'       => array('query' => 'value'),
            'post-fields' => array('param' => 'value'),
            'method'      => 'POST',
            'path'        => '/path/to/resource',
        );
        $storageData = array($serializedRequest);

        $storage->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($storageData));

        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $player = new Player($adapter, $storage);
        $player->play($output);
    }
}
