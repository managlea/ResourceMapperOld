<?php

namespace Managlea\CoreBundle\Tests;

use Managlea\CoreBundle\Utility\ResourceMapper;
use Managlea\TestingBundle\Utility\ResourceMapper\Baz as BazMapper;

class ResourceMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test
     * @expectedException \Exception
     */
    public function testGetSingleNoSourceObjectException()
    {
        new BazMapper;
    }

    /**
     * @expectedException \Exception
     */
    public function testGetMappedDataException()
    {
        ResourceMapper::getMappedData(array());
    }

    /**
     * @expectedException \Exception
     */
    public function testMapDataException()
    {
        $class = new \ReflectionClass('\Managlea\CoreBundle\Utility\ResourceMapper');
        $method = $class->getMethod('mapData');
        $method->setAccessible(true);

        $method->invoke(null, array());
    }
}