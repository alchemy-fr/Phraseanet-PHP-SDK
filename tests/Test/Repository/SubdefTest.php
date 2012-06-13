<?php

namespace Test\Repository;

require_once 'Repository.php';

use Guzzle;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;
use PhraseanetSDK\Repository\Subdef;
use PhraseanetSDK\Tools\Entity\Manager;

class SubdefTest extends Repository
{

    /**
     * @dataProvider subdefNameProvider
     */
    public function testFindByName($name)
    {
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'));

        $subdefRepository = new Subdef(new Manager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $subdef = $subdefRepository->findByName($record, $name);

        $this->assertTrue($subdef instanceof \PhraseanetSDK\Entity\Subdef);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindByNameException()
    {
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'));

        $subdefRepository = new Subdef(new Manager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $subdefRepository->findByName($record, 'unknowName');
    }

    public function testFindAll()
    {
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'));

        $subdefRepository = new Subdef(new Manager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $subdefs = $subdefRepository->findAll($record);

        $this->assertTrue($subdefs instanceof ArrayCollection);
        $this->assertEquals(5, $subdefs->count());
    }

    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindAllException()
    {
        $client = $this->getClient($this->getSampleResponse('401'));

        $subdefRepository = new Subdef(new Manager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $subdefRepository->findAll($record);
    }

    public function subdefNameProvider()
    {
        return array(
            array('preview'),
            array('thumbnail'),
            array('document'),
            array('preview_api'),
            array('thumbnailgif')
        );
    }

    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../ressources/response_samples/' . $filename . '.json';

        return file_get_contents($filename);
    }
}
