<?php

namespace Managlea\CoreBundle\Tests\ResourceMapper;

use Managlea\CoreBundle\Utility\RDBMS;
use Managlea\CoreBundle\Utility\Resource\Action as ActionResource;
use Managlea\CoreBundle\Utility\Resource\Action\Error as ErrorResource;
use Managlea\CoreBundle\Utility\Resource\Exception as ExceptionResource;
use Managlea\TestingBundle\Tests\ManagleaWebTestCase;
use Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Foo as FooMapper;

class DoctrineDeleteTest extends  ManagleaWebTestCase
{
    /**
     * Delete single resource without setting entity manager
     */
    public function testDeleteSingleNoEntityManagerException()
    {
        $mapper = new FooMapper;
        $resource = $mapper->deleteSingle($this->fooId);

        $this->assertEquals(true, $resource instanceof ExceptionResource);
        $this->assertEquals(true, $resource->getException() == 'Entity manager not set');
    }

    /**
     * Delete failed single resource (did not find entity)
     */
    public function testDeleteSingleFailNotFound()
    {
        $mapper = new FooMapper;
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true)));
        $resource = $mapper->deleteSingle($this->fooId);

        $this->assertEquals(false, $resource);
    }

    public function testDeleteSingleFlushException()
    {
        $mapper = new FooMapper;
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true, true, true)));
        $resource = $mapper->deleteSingle($this->fooId);

        $this->assertEquals(true, $resource instanceof ExceptionResource);
    }

    /**
     * Delete single resource successfully
     */
    public function testDeleteSingleSuccess()
    {
        $mapper = new FooMapper;
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true, true)));
        $resource = $mapper->deleteSingle($this->fooId);

        $this->assertEquals(true, $resource instanceof ActionResource);
        $this->assertEquals(false, $resource instanceof ErrorResource);
    }
}