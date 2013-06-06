<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Player;

class PlayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PhraseanetSDK\Recorder\Player::play
     */
    public function testPlay()
    {
        $client = $this->getMock('PhraseanetSDK\ClientInterface');
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

        $player = new Player($client, $storage);
        $player->play();
    }
}
