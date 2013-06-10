<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Filters\LimitFilter;

class LimitFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $data = array(1, 2, 3, 4);

        $filter = new LimitFilter(2);
        $filter->apply($data);

        $this->assertEquals(array(3, 4), $data);
    }
}
