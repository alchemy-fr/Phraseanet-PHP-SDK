<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Uploader;
use PhraseanetSDK\Http\ApiResponse;

class UploaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideUploadParameters
     */
    public function testUpload($file, $behavior, $coll, $status, $result, $expected)
    {
        $guzzle = $this->getMockBuilder('PhraseanetSDK\Http\APIGuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this->getMockBuilder('PhraseanetSDK\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $repo = $this->getMock('PhraseanetSDK\Repository\RepositoryInterface', array('findById'));

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

        $loader = new Uploader($guzzle, $em);
        $this->assertSame($expected, $loader->upload($file, $coll, $behavior, $status));
    }

    public function provideUploadParameters()
    {
        $coll = $this->getMockBuilder('PhraseanetSDK\Entity\DataboxCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $coll->expects($this->any())
            ->method('getBaseId')
            ->will($this->returnValue(42));

        $recordResponse = new ApiResponse(json_decode(json_encode(array(
            'meta' => array(),
            'response' => array(
                'entity' => '0',
                'url'    => '/records/1/42/',
            ),
        ))));

        $quarantineResponse = new ApiResponse(json_decode(json_encode(array(
            'meta' => array(),
            'response' => array(
                'entity' => '1',
                'url'    => '/quarantine/item/42/',
            ),
        ))));

        $record = $this->getMockBuilder('PhraseanetSDK\Entity\Record')
            ->disableOriginalConstructor()
            ->getMock();

        $quarantine = $this->getMockBuilder('PhraseanetSDK\Entity\Quarantine')
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
