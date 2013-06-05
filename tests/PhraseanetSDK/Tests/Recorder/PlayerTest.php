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
        $client = $this->getMock('Guzzle\Http\ClientInterface');
        $storage = $this->getMock('PhraseanetSDK\Recorder\Storage\StorageInterface');
        $serializer = $this->getMock('PhraseanetSDK\Recorder\RequestSerializer');
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $serializedRequest = array('serialized request');
        $storageData = array($serializedRequest);

        $storage->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($storageData));
        $serializer->expects($this->once())
            ->method('unserialize')
            ->with($client, $serializedRequest)
            ->will($this->returnValue($request));
        $request->expects($this->once())
            ->method('send');

        $player = new Player($client, $storage, $serializer);
        $player->play();
    }
}
