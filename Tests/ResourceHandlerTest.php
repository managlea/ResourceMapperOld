<?php

namespace Managlea\ResourceHandler\Tests;

class Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function IsInstanceOfInterface()
    {
        $mock = $this->getMockForAbstractClass('Managlea\ResourceHandler\ResourceHandler');
        $this->assertInstanceOf('Managlea\ResourceHandler\ResourceHandlerInterface', $mock);
    }

    /**
     * @test
     */
    public function getSingle()
    {
        $mock = $this->getMockForAbstractClass('Managlea\ResourceHandler\ResourceHandler');
        $mock->expects($this->any())
            ->method('findResource')
            ->will($this->returnValue(true));
        $this->assertEquals(true, $mock->getSingle(1));

        $mock = $this->getMockForAbstractClass('Managlea\ResourceHandler\ResourceHandler');
        $mock->expects($this->any())
            ->method('findResource')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->getSingle(1));
    }

    /**
     * @test
     */
    public function getCollection()
    {
        $mock = $this->getMockForAbstractClass('Managlea\ResourceHandler\ResourceHandler');
        $mock->expects($this->any())
            ->method('findResourceCollection')
            ->will($this->returnValue(array()));
        $this->assertEquals(array(), $mock->getCollection());

        $mock = $this->getMockForAbstractClass('Managlea\ResourceHandler\ResourceHandler');
        $mock->expects($this->any())
            ->method('findResourceCollection')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->getCollection());
    }
}