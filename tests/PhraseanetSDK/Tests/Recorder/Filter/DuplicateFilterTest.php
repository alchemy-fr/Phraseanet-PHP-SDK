<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Filters\DuplicateFilter;

class DuplicateFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $data = array(1, 2, 3, 4, 5, 5, 4, 1, 3, 2, 1);

        $filter = new DuplicateFilter();
        $filter->apply($data);

        $this->assertEquals(array(5, 4, 3, 2, 1), $data);
    }
}
