<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Filters\MonitorFilter;

class MonitorFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $data = array(
            array(
                'path' => '/api/v1/records/search/',
            ),
            array(
                'path' => '/api/v1/monitor/scheduler/',
            ),
            array(
                'path' => '/api/v1/monitor/tasks/',
            ),
            array(
                'path' => '/api/v1/search/',
            ),
        );

        $filter = new MonitorFilter(2);
        $filter->apply($data);

        $expected = array(
            array(
                'path' => '/api/v1/records/search/',
            ),
            array(
                'path' => '/api/v1/search/',
            ),
        );

        $this->assertEquals($expected, $data);
    }
}
