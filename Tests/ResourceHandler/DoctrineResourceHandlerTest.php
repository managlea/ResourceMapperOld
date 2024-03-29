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

    protected $entityManager;


    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder(self::CLASS_NAMESPACE . 'EntityManagerInterface')
            ->getMock();

        $this->resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $this->entityManager
        );
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function findResourceNoRepo()
    {
        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $this->entityManager
        );

        $resourceHandler->getSingle(1);
    }

    /**
     * @test
     */
    public function findResourceNoEntity()
    {
        $this->entityManager->method('getRepository')
            ->willReturn($this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                ->disableOriginalConstructor()
                ->getMock());

        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $this->entityManager
        );

        $this->assertEquals(false, $resourceHandler->getSingle(1));
    }

    /**
     * @test
     */
    public function findResource()
    {
        $foo =  $this->getMockBuilder('Foo')
            ->getMock();
        $repo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repo->method('find')
            ->willReturn($foo);
        $this->entityManager->method('getRepository')
            ->willReturn($repo);

        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $this->entityManager
        );

        $this->assertEquals($foo, $resourceHandler->getSingle(1));
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function findResourceCollectionNoRepo()
    {
        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $this->entityManager
        );
        $resourceHandler->getCollection();
    }
    /**
     * @test
     */
    public function findResourceCollection()
    {
        $repo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repo->method('findBy')
            ->willReturn(array());
        $this->entityManager->method('getRepository')
            ->willReturn($repo);
        $resourceHandler = new DoctrineResourceHandler(
            $this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'),
            $this->entityManager
        );

        $resourceHandler->getCollection();
        $this->assertEquals(array(), $resourceHandler->getCollection());
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