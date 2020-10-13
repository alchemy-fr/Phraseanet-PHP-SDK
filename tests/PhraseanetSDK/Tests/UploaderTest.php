<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Uploader;
use PhraseanetSDK\Http\APIResponse;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Repository\RepositoryInterface;
use PhraseanetSDK\Entity\DataboxCollection;
use PhraseanetSDK\Entity\Record;
use PhraseanetSDK\Entity\Quarantine;
use PhraseanetSDK\EntityManager;

class UploaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideUploadParameters
     */
    public function testUpload($file, $behavior, $coll, $status, $result, $expected)
    {
        $guzzle = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repo = $this->createMock('PhraseanetSDK\Repository\RepositoryInterface', array('findById'));

        $em->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($repo));

        $repo->expects($this->once())
            ->method('findById')
            ->will($this->returnValue($expected));

        $guzzle->expects($this->once())
            ->method('call')
            ->with('POST', 'v1/records/add/', array(), $this->isType('array'), array('file' => $file))
            ->will($this->returnValue($result));

        /** @var APIGuzzleAdapter $guzzle */
        /** @var EntityManager $em */
        $loader = new Uploader($guzzle, $em);
        $this->assertSame($expected, $loader->upload($file, $coll, $behavior, $status));
    }

    public function provideUploadParameters()
    {
        $coll = $this->getMockBuilder(DataboxCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $coll->expects($this->any())
            ->method('getBaseId')
            ->will($this->returnValue(42));

        $recordResponse = new APIResponse(json_decode(json_encode(array(
            'meta' => array(),
            'response' => array(
                'entity' => '0',
                'url'    => '/records/1/42/',
            ),
        ))));

        $quarantineResponse = new APIResponse(json_decode(json_encode(array(
            'meta' => array(),
            'response' => array(
                'entity' => '1',
                'url'    => '/quarantine/item/42/',
            ),
        ))));

        $record = $this->getMockBuilder(Record::class)
            ->disableOriginalConstructor()
            ->getMock();

        $quarantine = $this->getMockBuilder(Quarantine::class)
            ->disableOriginalConstructor()
            ->getMock();

        return array(
            array(__FILE__, 0, 42, '010101110001000', $recordResponse, $record),
            array(__FILE__, null, 42, '010101110001000', $recordResponse, $record),
            array(__FILE__, null, $coll, '010101110001000', $recordResponse, $record),
            array(__FILE__, null, $coll, null, $recordResponse, $record),
            array(__FILE__, 1, 42, '010101110001000', $quarantineResponse, $quarantine),
            array(__FILE__, null, 42, '010101110001000', $quarantineResponse, $quarantine),
        );
    }
}
