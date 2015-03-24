<?php

namespace Managlea\CoreBundle\Tests\ResourceMapper;

use Managlea\CoreBundle\Utility\RDBMS;
use Managlea\CoreBundle\Utility\Resource\Data\Single as SingleResource;
use Managlea\CoreBundle\Utility\Resource\Data\Collection as ResourceCollection;
use Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Foo as FooMapper;

/**
 * Class DoctrineTest
 * @package Managlea\CoreBundle\Tests\ResourceMapper
 */
class DoctrineGetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int $fooId Mocked object id
     */
    private $fooId;

    public function setUp()
    {
        $this->fooId = rand(1,999);
    }

    /**
     * Try to get Resource without setting RDBMS for ResourceMapper
     * @expectedException \Exception
     */
    public function testGetSingleNoEntityManagerException()
    {
        $fooMapper = new FooMapper;
        $fooMapper->getSingle(1);
    }
    /**
     * @expectedException \Exception
     */
    public function testGetCollectionNoEntityManagerException()
    {
        $fooMapper = new FooMapper;
        $fooMapper->getCollection(array());
    }
    /**
     * Try setting RDBMS of ResourceMapper to unsupported type
     * @expectedException \Exception
     */
    public function testSetEntityManagerException()
    {
        $fooMapper = new FooMapper;
        $rdbms = new RDBMS($fooMapper);
        $fooMapper->setRDBMS($rdbms);
    }
    /**
     * @expectedException \Exception
     */
    public function testGetSingleNoRepoException()
    {
        $entityManager = $this->getEntityManagerMock();

        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS(new RDBMS($entityManager));
        $fooMapper->getSingle($this->fooId);
    }
    /**
     * Create ResourceMapper, set RDBMS and try to get single Resource
     */
    public function testGetSingleSuccess()
    {
        $entityManager = $this->getEntityManagerMock(true, true);

        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS(new RDBMS($entityManager));
        $resource = $fooMapper->getSingle($this->fooId);

        $this->assertEquals(true, $resource instanceof SingleResource);
        $this->assertEquals(true, $resource->getId() == $this->fooId);
    }

    public function testGetSingleFail()
    {
        $entityManager = $this->getEntityManagerMock(true);

        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS(new RDBMS($entityManager));
        $resource = $fooMapper->getSingle($this->fooId);

        $this->assertEquals(true, !$resource);
    }
    /**
     * @expectedException \Exception
     */
    public function testGetCollectionNoRepositoryException()
    {
        $entityManager = $this->getEntityManagerMock();

        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS(new RDBMS($entityManager));
        $fooMapper->getCollection(array());
    }

    public function testGetCollectionEmpty()
    {
        $entityManager = $this->getEntityManagerMock(true);

        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS(new RDBMS($entityManager));
        $collection = $fooMapper->getCollection(array());
        $collectionData = $collection->getData();

        $this->assertEquals(true, $collection instanceof ResourceCollection);
        $this->assertEquals(true, $collection->getCount() == 0);
        $this->assertEquals(true, empty($collectionData));
    }

    public function testGetCollectionSuccess()
    {
        $entityManager = $this->getEntityManagerMock(true, true);

        $fooMapper = new FooMapper;
        $fooMapper->setRDBMS(new RDBMS($entityManager));
        $collection = $fooMapper->getCollection(array());

        $this->assertEquals(true, $collection instanceof ResourceCollection);
        $this->assertEquals(true, $collection->getCount() == 1);

        $filters = $collection->getFilters();
        $this->assertEquals(true, empty($filters));

        $collectionData = $collection->getData();
        $this->assertEquals(true, !empty($collectionData));

        $resource = current($collectionData);
        $this->assertEquals(true, $resource instanceof SingleResource);
        $this->assertEquals(true, $resource->getId() == $this->fooId);
    }

    public function testStaticMappingSuccess()
    {
        $foo = new \Managlea\TestingBundle\Entity\Foo();
        $foo->setName('Foo Name');

        $mappedFoo = FooMapper::getMappedData($foo);
        $this->assertEquals(true, $mappedFoo['first_name'] == $foo->getName());
    }

    /**
     * @expectedException \Exception
     */
    public function testStaticMappingException()
    {
        $bar = new \Managlea\TestingBundle\Entity\Bar();
        $bar->setName('Foo Name');

        FooMapper::getMappedData($bar);
    }

    private function getEntityManagerMock($mockRepo = false, $mockFoo = false)
    {
        $foo = null;
        $fooCollection = null;
        $fooRepository = null;

        if ($mockFoo)
        {
            $foo = $this->getMock('\Managlea\TestingBundle\Entity\Foo');
            $foo->expects($this->any())
                ->method('getId')
                ->will($this->returnValue($this->fooId));
            $foo->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('Random Joe'));

            $fooCollection = array($foo);
        }

        if ($mockRepo)
        {
            $fooRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                ->disableOriginalConstructor()
                ->getMock();
            $fooRepository->expects($this->any())
                ->method('findBy')
                ->will($this->returnValue($fooCollection));
            $fooRepository->expects($this->any())
                ->method('find')
                ->will($this->returnValue($foo));
        }

        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($fooRepository));

        return $entityManager;
    }
}