<?php

namespace Managlea\Component\Tests\ResourceHandlerTest;

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
        $this->resourceHandler = new DoctrineResourceHandler($this->getMock(self::CLASS_NAMESPACE . 'ResourceFactoryInterface'));
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function findResource()
    {
        $this->resourceHandler->getSingle(1);
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