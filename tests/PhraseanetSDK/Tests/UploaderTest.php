<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Uploader;
use PhraseanetSDK\Http\APIResponse;

class UploaderTest extends \PHPUnit\Framework\TestCase
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

        $repo = $this->getMockBuilder(UploaderRepositoryStub::class)->onlyMethods(['findById'])->getMock();

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

    public static function provideUploadParameters()
    {
        $coll = \PhraseanetSDK\Entity\DataboxCollection::fromValue((object) array('base_id' => 42));

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

        $record = (new \ReflectionClass('PhraseanetSDK\Entity\Record'))->newInstanceWithoutConstructor();

        $quarantine = (new \ReflectionClass('PhraseanetSDK\Entity\Quarantine'))->newInstanceWithoutConstructor();

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

class UploaderRepositoryStub
{
    public function findById(...$arguments)
    {
    }
}
