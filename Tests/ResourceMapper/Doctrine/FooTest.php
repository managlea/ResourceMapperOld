<?php

namespace Managlea\TestingBundle\Tests\ResourceMapper\Doctrine;

use Managlea\CoreBundle\Utility\RDBMS;
use Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Foo as FooMapper;
use Managlea\CoreBundle\Utility\Resource;

/**
 * Class FooTest
 * @package Managlea\TestingBundle\Tests\ResourceMapper\Doctrine
 * @todo this test should actually run against Database and real Entity
 */
class FooTest extends \PHPUnit_Framework_TestCase
{
    public function testFoo()
    {
        // Create random id for mocked entity
        $fooId = rand(1,999);

        $foo = $this->getMock('\Managlea\TestingBundle\Entity\Foo');
        $foo->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($fooId));
        $foo->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Random Joe'));

        $fooRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $fooRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($foo));

        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($fooRepository));

        $rdbms = new RDBMS($entityManager);

        // Create new mapper, set entity manager and get single resource
        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS($rdbms);
        $resource = $fooMapper->getSingle($fooId);

        // Resource returned is an array
        $this->assertEquals(true, $resource instanceof Resource);
        // Resource id returned is same as set to mocked entity
        $this->assertEquals(true, $resource->getId() == $fooId);
    }
}