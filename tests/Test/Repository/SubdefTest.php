<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Subdef;
use PhraseanetSDK\EntityManager;

class SubdefTest extends Repository
{

    /**
     * @dataProvider subdefNameProvider
     */
    public function testFindSubdefByRecordByName($name)
    {
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'));
        $subdefRepository = new Subdef(new EntityManager($client));
        $subdef = $subdefRepository->findByRecordAndName(1, 1, $name);
        $this->checkSubdef($subdef);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\NotFoundException
     */
    public function testFindSubdefByRecordByNameException()
    {
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'), 200);

        $subdefRepository = new Subdef(new EntityManager($client));
        $subdefRepository->findByRecordAndName(1, 1, 'unknowName');
    }

    public function testFindSubdefByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'));
        $subdefRepository = new Subdef(new EntityManager($client));
        $subdefs = $subdefRepository->findByRecord(1, 1, array('screen'), array('image/jpg'));
        $this->assertIsCollection($subdefs);

        foreach ($subdefs as $subdef) {
            $this->checkSubdef($subdef);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindSubdefByRecordUnauthorizedRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $subdefRepository = new Subdef(new EntityManager($client));
        $subdefRepository->findByRecord(1, 1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindSubdefByRecordUnauthorizedException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $subdefRepository = new Subdef(new EntityManager($client));
        $subdefRepository->findByRecord(1, 1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindSubdefByRecordRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('500'), 500);
        $subdefRepository = new Subdef(new EntityManager($client));
        $subdefRepository->findByRecord(1, 1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testQueryRuntimeException()
    {
        //throw curl exception
        $client = $this->getClient($this->getSampleResponse('repository/subdef/findAll'), 200, true);
        $subdefRepository = new Subdef(new EntityManager($client));
        $subdefRepository->findByRecord(1, 1);
    }

    public function subdefNameProvider()
    {
        return array(
            array('preview'),
            array('thumbnail'),
            array('document'),
            array('thumbnail_mobile'),
            array('preview_mobile')
        );
    }
}
