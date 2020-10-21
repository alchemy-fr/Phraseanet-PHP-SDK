<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Monitor;
use PhraseanetSDK\Monitor\Scheduler;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Monitor\Task;
use PhraseanetSDK\Http\APIResponse;
use PhraseanetSDK\Http\APIGuzzleAdapter;

class MonitorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetScheduler()
    {
        $adapter = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adapter->expects($this->once())
            ->method('call')
            ->with('GET', array('v1/monitor/scheduler/', array()))
            ->will($this->returnValue($this->getFixture('scheduler')));

        /** @var APIGuzzleAdapter $adapter */
        $mon = new Monitor($adapter);

        $expected = new Scheduler();
        $expected->setPid(90555)
            ->setState('started')
            ->setUpdatedOn(\DateTime::createFromFormat(DATE_ATOM, '2012-08-31T15:28:29+02:00'));

        $this->assertEquals($expected, $mon->getScheduler());
    }

    public function testGetTasks()
    {
        $adapter = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adapter->expects($this->once())
            ->method('call')
            ->with('GET', array('v1/monitor/tasks/', array()))
            ->will($this->returnValue($this->getFixture('tasks')));

        /** @var APIGuzzleAdapter $adapter */
        $mon = new Monitor($adapter);

        $expected = new ArrayCollection();

        $task1 = new Task();
        $task1->setId(1)
            ->setName('Ecriture des métas-données')
            ->setState('started')
            ->setPid(15707)
            ->setTitle('newTitle1985032899')
            ->setLastExecTime(\DateTime::createFromFormat(DATE_ATOM, '2012-06-13T14:38:02+02:00'))
            ->setAutoStart(true)
            ->setRunner('scheduler')
            ->setCrashCounter(0);

        $task2 = new Task();
        $task2->setId(2)
            ->setName('Création des sous définitions')
            ->setState('started')
            ->setPid(15705)
            ->setTitle('Subviews creation')
            ->setLastExecTime(\DateTime::createFromFormat(DATE_ATOM, '2012-06-13T14:37:38+02:00'))
            ->setAutoStart(true)
            ->setRunner('scheduler')
            ->setCrashCounter(0);

        $expected->add($task1);
        $expected->add($task2);

        $this->assertEquals($expected, $mon->getTasks());
    }

    public function testGetTask()
    {
        $task_id = 2;

        $adapter = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adapter->expects($this->once())
            ->method('call')
            ->with('GET', array('v1/monitor/task/{task_id}/', array('task_id' => $task_id)))
            ->will($this->returnValue($this->getFixture('task')));

        /** @var APIGuzzleAdapter $adapter */
        $mon = new Monitor($adapter);

        $expected = new Task();
        $expected->setId(2)
            ->setName('Création des sous définitions')
            ->setState('started')
            ->setPid(15705)
            ->setTitle('Subviews creation')
            ->setLastExecTime(\DateTime::createFromFormat(DATE_ATOM, '2012-06-13T14:38:38+02:00'))
            ->setAutoStart(true)
            ->setRunner('scheduler')
            ->setCrashCounter(0);

        $this->assertEquals($expected, $mon->getTask($task_id));
    }

    public function testStartTask()
    {
        $task_id = 2;

        $adapter = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adapter->expects($this->once())
            ->method('call')
            ->with('POST', array('v1/monitor/task/{task_id}/start/', array('task_id' => $task_id)))
            ->will($this->returnValue($this->getFixture('start-task')));

        /** @var APIGuzzleAdapter $adapter */
        $mon = new Monitor($adapter);

        $expected = new Task();
        $expected->setId(2)
            ->setName('Création des sous définitions')
            ->setState('started')
            ->setPid(15784)
            ->setTitle('Subviews creation')
            ->setLastExecTime(\DateTime::createFromFormat(DATE_ATOM, '2012-06-13T14:42:38+02:00'));

        $this->assertEquals($expected, $mon->startTask($task_id));
    }

    public function testStopTask()
    {
        $task_id = 2;

        $adapter = $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $adapter->expects($this->once())
            ->method('call')
            ->with('POST', array('v1/monitor/task/{task_id}/stop/', array('task_id' => $task_id)))
            ->will($this->returnValue($this->getFixture('stop-task')));

        /** @var APIGuzzleAdapter $adapter */
        $mon = new Monitor($adapter);

        $expected = new Task();
        $expected->setId(2)
            ->setName('Création des sous définitions')
            ->setState('tostop')
            ->setPid(15784)
            ->setTitle('Subviews creation')
            ->setLastExecTime(\DateTime::createFromFormat(DATE_ATOM, '2012-06-13T14:42:38+02:00'));

        $this->assertEquals($expected, $mon->stopTask($task_id));
    }

    private function getFixture($name)
    {
        return new APIResponse(json_decode(file_get_contents(__DIR__.'/../../resources/response_samples/monitor/'.$name.'.json')));
    }
}
