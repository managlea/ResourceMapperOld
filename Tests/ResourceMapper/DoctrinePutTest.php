<?php

namespace Managlea\CoreBundle\Tests\ResourceMapper;

use Managlea\CoreBundle\Utility\RDBMS;
use Managlea\CoreBundle\Utility\Resource\Action as ActionResource;
use Managlea\CoreBundle\Utility\Resource\Action\Error as ErrorResource;
use Managlea\CoreBundle\Utility\Resource\Exception as ExceptionResource;
use Managlea\TestingBundle\Tests\ManagleaWebTestCase;
use Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Foo as FooMapper;

class DoctrinePutTest extends ManagleaWebTestCase
{
    /**
     * Post single resource without setting form factory
     * @expectedException \Exception
     */
    public function testPutSingleNoFormFactoryException()
    {
        $data = array();

        $mapper = new FooMapper;
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true, true)));
        $mapper->putSingle($this->fooId, $data);
    }

    /**
     * Put single resource without setting entity manager
     */
    public function testPutSingleNoEntityManagerException()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => 'random joe');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $resource = $mapper->putSingle($this->fooId, $data);

        $this->assertEquals(true, $resource instanceof ExceptionResource);
        $this->assertEquals(true, $resource->getException() == 'Entity manager not set');
    }

    public function testPutSingleFlushException()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => 'something else');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true, true, true)));
        $resource = $mapper->putSingle($this->fooId, $data);

        $this->assertEquals(true, $resource instanceof ExceptionResource);
    }

    /**
     * Post single resource successfully
     */
    public function testPutSingleSuccess()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => 'something else');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true, true)));
        $resource = $mapper->putSingle($this->fooId, $data);

        $this->assertEquals(true, $resource instanceof ActionResource);
        $this->assertEquals(false, $resource instanceof ErrorResource);
    }

    /**
     * Put failed single resource (did not find entity)
     */
    public function testPutSingleFailNotFound()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => '');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true)));
        $resource = $mapper->putSingle($this->fooId, $data);

        $this->assertEquals(false, $resource);
    }

    public function testPutSingleFailError()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => '');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock(true, true)));
        $resource = $mapper->putSingle($this->fooId, $data);

        $this->assertEquals(true, $resource instanceof ErrorResource);
    }
}