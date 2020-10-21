<?php

namespace PhraseanetSDK\Tests\Recorder;

use PHPUnit_Framework_TestCase;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Recorder\Player;
use PhraseanetSDK\Recorder\Storage\StorageInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlayerTest extends PHPUnit_Framework_TestCase
{
    public function testPlay()
    {
        $adapter = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storage = $this->createMock(StorageInterface::class);

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

        $output = $this->createMock(OutputInterface::class);

        /** @var APIGuzzleAdapter $adapter
         *  @var StorageInterface $storage
         */
        $player = new Player($adapter, $storage);

        /** @var OutputInterface $output */
        $player->play($output);
    }
}
