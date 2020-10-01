<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Player;

class PlayerTest extends \PHPUnit_Framework_TestCase
{
    public function testPlay()
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\APIGuzzleAdapter')
            ->disableOriginalConstructor()
            ->createMock();
        $storage = $this->createMock('PhraseanetSDK\Recorder\Storage\StorageInterface');

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

        $output = $this->createMock('Symfony\Component\Console\Output\OutputInterface');

        $player = new Player($adapter, $storage);
        $player->play($output);
    }
}
