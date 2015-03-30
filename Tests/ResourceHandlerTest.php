<?php

namespace Managlea\Component\Tests;

class Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function isInstanceOfInterface()
    {
        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $this->assertInstanceOf('Managlea\Component\ResourceHandlerInterface', $mock);
    }

    /**
     * @test
     */
    public function getSingle()
    {
        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResource')
            ->will($this->returnValue(true));
        $this->assertEquals(true, $mock->getSingle(1));

        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResource')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->getSingle(1));
    }

    /**
     * @test
     */
    public function getCollection()
    {
        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResourceCollection')
            ->will($this->returnValue(array()));
        $this->assertEquals(array(), $mock->getCollection());

        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResourceCollection')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->getCollection());
    }

    /**
     * @test
     */
    public function postSingle()
    {
        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('createResource')
            ->will($this->returnValue(true));
        $this->assertEquals(true, $mock->postSingle(array()));

        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('createResource')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->postSingle(array()));
    }

    /**
     * @test
     */
    public function putSingle()
    {
        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResource')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->putSingle(1, array()));

        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResource')
            ->will($this->returnValue(true));
        $mock->expects($this->once())
            ->method('updateResource')
            ->will($this->returnValue(true));
        $this->assertEquals(true, $mock->putSingle(1, array()));
    }

    /**
     * @test
     */
    public function deleteSingle()
    {
        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResource')
            ->will($this->returnValue(false));
        $this->assertEquals(false, $mock->deleteSingle(1, array()));

        $mock = $this->getMockForAbstractClass('Managlea\Component\ResourceHandler');
        $mock->expects($this->once())
            ->method('findResource')
            ->will($this->returnValue(true));
        $mock->expects($this->once())
            ->method('removeResource');
        $this->assertEquals(true, $mock->deleteSingle(1, array()));
    }
}
