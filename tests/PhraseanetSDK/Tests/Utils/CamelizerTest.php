<?php

namespace PhraseanetSDK\Tests\Utils;

use PhraseanetSDK\Utils\Camelizer;

class CamelizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCamelizedData
     */
    public function testCamelize($expected, $value)
    {
        $camelizer = new Camelizer();
        $this->assertEquals($expected, $camelizer->camelize($value));
    }

    public function provideCamelizedData()
    {
        return array(
            array('RecordStatus', 'record-status'),
            array('RecordStatus', 'record_status'),
            array('Record', 'record'),
        );
    }
}
