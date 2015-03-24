<?php

namespace Managlea\CoreBundle\Tests\ResourceMapper;

use Managlea\CoreBundle\Utility\RDBMS;
use Managlea\CoreBundle\Utility\Resource\Action as ActionResource;
use Managlea\CoreBundle\Utility\Resource\Action\Error as ErrorResource;
use Managlea\CoreBundle\Utility\Resource\Exception as ExceptionResource;
use Managlea\TestingBundle\Tests\ManagleaWebTestCase;
use Managlea\TestingBundle\Utility\ResourceMapper\Doctrine\Foo as FooMapper;

class DoctrinePostTest extends ManagleaWebTestCase
{
    /**
     * Post single resource without setting form factory
     * @expectedException \Exception
     */
    public function testPostSingleNoFormFactoryException()
    {
        $data = array();

        $mapper = new FooMapper;
        $mapper->postSingle($data);
    }

    /**
     * Post single resource without setting entity manager
     */
    public function testPostSingleNoEntityManagerException()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => 'random joe');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $resource = $mapper->postSingle($data);

        $this->assertEquals(true, $resource instanceof ExceptionResource);
        $this->assertEquals(true, $resource->getException() == 'Entity manager not set');
    }

    /**
     * Post single resource successfully
     */
    public function testPostSingleSuccess()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => 'random joe');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock()));
        $resource = $mapper->postSingle($data);

        $this->assertEquals(true, $resource instanceof ActionResource);
    }

    /**
     * Post failed single resource
     */
    public function testPostSingleFail()
    {
        $formBuilder = $this->formFactory;
        $data = array('name' => '');

        $mapper = new FooMapper;
        $mapper->setFormFactory($formBuilder);
        $mapper->setRDBMS(new RDBMS($this->getEntityManagerMock()));
        $resource = $mapper->postSingle($data);

        $this->assertEquals(true, $resource instanceof ErrorResource);
    }
}