<?php

namespace Managlea\Component\Tests\ResourceHandler;

use Managlea\Component\ResourceHandler\DoctrineResourceHandler;

class DoctrineResourceHandlerTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_NAMESPACE = 'Managlea\\Component\\';

    /**
     * @var DoctrineResourceHandler
     */
    protected $resourceHandler;


    protected function setUp()
    {
        $entityManager = $this->getMockBuilder(self::CLASS_NAMESPACE . 'EntityManagerInterface')
            ->getMock();

        $this->resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $entityManager
        );
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function findResourceException()
    {
        $entityManager = $this->getMockBuilder(self::CLASS_NAMESPACE . 'EntityManagerInterface')
            ->getMock();

        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $entityManager
        );

        $resourceHandler->getSingle(1);
    }

    /**
     * @test
     */
    public function findResourceNoEntity()
    {
        $entityManager = $this->getMockBuilder(self::CLASS_NAMESPACE . 'EntityManagerInterface')
            ->getMock();
        $entityManager->method('getRepository')
            ->willReturn($this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                ->disableOriginalConstructor()
                ->getMock());

        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $entityManager
        );

        $this->assertEquals(false, $resourceHandler->getSingle(1));
    }

    /**
     * @test
     */
    public function findResource()
    {
        $entityManager = $this->getMockBuilder(self::CLASS_NAMESPACE . 'EntityManagerInterface')
            ->getMock();
        $repo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repo->method('find')
            ->willReturn('foo');
        $entityManager->method('getRepository')
            ->willReturn($repo);

        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $entityManager
        );

        $this->assertEquals('foo', $resourceHandler->getSingle(1));
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function findResourceCollection()
    {
        $this->resourceHandler->getCollection();
    }

    /**
     * @test
     * @@expectedException \Exception
     */
    public function createResource()
    {
        $this->resourceHandler->postSingle(array());
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function updateResource()
    {
        $this->resourceHandler->putSingle(1, array());
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function removeResource()
    {
        $this->resourceHandler->deleteSingle(1);
    }
}