<?php

namespace PhraseanetSDK\Tests\Entity;

use PhraseanetSDK\Entity\Record;

class RecordTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSubdefsByDevicesAndMimeTypes()
    {
        $recordId = 2;
        $databoxId = 1;

        $devices = array('tablet');
        $mimes = array('video/ogg');

        $em = $this->getEntityManagerMock();

        $subdefRepoMock = $this->getMock(
            'PhraseanetSDK\\Repository\\Subdef'
            , array()
            , array()
            , ''
            , false
        );

        $subdefRepoMock->expects($this->once())
            ->method('findByRecord')
            ->with(
                $this->equalTo($databoxId),
                $this->equalTo($recordId),
                $devices,
                $mimes
            )->will($this->returnValue('test'));

        $em->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('subdef'))
            ->will($this->returnValue($subdefRepoMock));

        $record = new Record($em);
        $record->setDataboxId($databoxId);
        $record->setRecordId($recordId);

        $returnValue = $record->getSubdefsByDevicesAndMimeTypes($devices, $mimes);

        $this->assertEquals('test', $returnValue);
    }

    private function getEntityManagerMock()
    {
        return $this->getMock(
            'PhraseanetSDK\\EntityManager'
            , array()
            , array()
            , ''
            , false
        );
    }
}
