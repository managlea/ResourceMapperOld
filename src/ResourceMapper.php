<?php

namespace Managlea\ResourceMapper;

//use Managlea\CoreBundle\Utility\Resource\Action as ActionResource;
//use Managlea\CoreBundle\Utility\Resource\Action\Error as ErrorResource;
//use Managlea\CoreBundle\Utility\Resource\Data\Single as SingleResource;
//use Managlea\CoreBundle\Utility\Resource\Data\Collection as ResourceCollection;
//use Managlea\CoreBundle\Utility\Resource\Exception as ExceptionResource;
//use Symfony\Component\Config\Definition\Exception\Exception;
//use Symfony\Component\Form\Form;
//use Symfony\Component\Form\FormFactory;

abstract class ResourceMapper implements ResourceMapperInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    const SOURCE_OBJECT_NAMESPACE = false;
    const SOURCE_OBJECT_FORM_NAMESPACE = false;

    abstract protected function persistEntity($entity);

    abstract protected function removeEntity($entity);

    abstract protected function findEntity($entityId);

    abstract protected function findEntityCollection(array $filters);

    public static function getMappedData($entity)
    {
        throw new Exception(__FUNCTION__ . ' is not implemented');
    }

    protected static function mapData($entity)
    {
        throw new Exception(__FUNCTION__ . ' is not implemented');
    }

    public function __construct()
    {
        if (!static::SOURCE_OBJECT_NAMESPACE || !static::SOURCE_OBJECT_FORM_NAMESPACE) {
            throw new Exception('Source object or object form namespace not set');
        }
    }

    /**
     * @param FormFactory $formFactory
     */
    final public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param $resourceId
     * @return bool|Resource
     */
    public function getSingle($resourceId)
    {
        $entity = $this->findEntity($resourceId);

        if (!$entity) {
            return false;
        }

        $resource = new SingleResource(
            $resourceId,
            static::SOURCE_OBJECT_NAMESPACE,
            static::getMappedData($entity)
        );

        return $resource;
    }

    /**
     * @todo using ->getID() is not the smartest thing?
     * @param array $filters
     * @return ResourceCollection
     */
    public function getCollection(array $filters)
    {
        $entityCollection = $this->findEntityCollection($filters);
        $data = array();

        if (!empty($entityCollection)) {
            foreach ($entityCollection as $entity) {
                $data[] = new SingleResource(
                    $entity->getId(),
                    static::SOURCE_OBJECT_NAMESPACE,
                    static::getMappedData($entity)
                );
            }
        }

        return new ResourceCollection(
            $filters,
            static::SOURCE_OBJECT_NAMESPACE,
            $data
        );
    }

    /**
     * Create new resource
     * @param array $data
     * @return ActionResource|ErrorResource|ExceptionResource
     */
    public function postSingle(array $data)
    {
        // Create new object based on mapper source object
        $entityClassName = static::SOURCE_OBJECT_NAMESPACE;
        $entity = new $entityClassName;

        // Create form and bind data and entity together
        $form = $this->createForm($entity, $data);

        if ($form->isValid()) {
            try {
                $this->persistEntity($entity);
                $resource = new ActionResource(
                    static::SOURCE_OBJECT_NAMESPACE,
                    'post',
                    static::getMappedData($entity)
                );
            } catch (Exception $e) {
                $resource = new ExceptionResource(
                    static::SOURCE_OBJECT_NAMESPACE,
                    $e->getMessage()
                );
            }
        } else {
            $resource = new ErrorResource(
                static::SOURCE_OBJECT_NAMESPACE,
                'post',
                $this->getFormErrors($form)
            );
        }

        return $resource;
    }

    /**
     * Update resource
     */
    public function putSingle($resourceId, array $data)
    {
        try {
            // Search for object
            $entity = $this->findEntity($resourceId);
        } catch (Exception $e) {
            $resource = new ExceptionResource(
                static::SOURCE_OBJECT_NAMESPACE,
                $e->getMessage()
            );

            return $resource;
        }

        if (!$entity) {
            return false;
        }

        // Create form and bind data and entity together
        $form = $this->createForm($entity, $data);

        if ($form->isValid()) {
            try {
                $this->persistEntity($entity);
                $resource = new ActionResource(
                    static::SOURCE_OBJECT_NAMESPACE,
                    'put',
                    static::getMappedData($entity)
                );
            } catch (Exception $e) {
                $resource = new ExceptionResource(
                    static::SOURCE_OBJECT_NAMESPACE,
                    $e->getMessage()
                );
            }
        } else {
            $resource = new ErrorResource(
                static::SOURCE_OBJECT_NAMESPACE,
                'put',
                $this->getFormErrors($form)
            );
        }

        return $resource;
    }

    /**
     * Delete resource
     */
    public function deleteSingle($resourceId)
    {
        try {
            // Search for object
            $entity = $this->findEntity($resourceId);
        } catch (Exception $e) {
            $resource = new ExceptionResource(
                static::SOURCE_OBJECT_NAMESPACE,
                $e->getMessage()
            );

            return $resource;
        }

        if (!$entity) {
            return false;
        }

        // Delete resource
        try {
            $this->removeEntity($entity);
            $resource = new ActionResource(
                static::SOURCE_OBJECT_NAMESPACE,
                'delete'
            );
        } catch (Exception $e) {
            $resource = new ExceptionResource(
                static::SOURCE_OBJECT_NAMESPACE,
                $e->getMessage()
            );
        }

        return $resource;
    }

    /**
     * @param $entity
     * @param array $data
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    private function createForm($entity, array $data)
    {
        if (!$this->formFactory) {
            throw new Exception('Form factory not set');
        }

        // Create new form type
        $formTypeName = static::SOURCE_OBJECT_FORM_NAMESPACE;
        $formType = new $formTypeName;

        // Build form by type and new object
        $form = $this->formFactory->create($formType, $entity);

        // Submit data to form
        $form->submit($data);

        return $form;
    }

    /**
     * @param Form $form
     * @return array
     */
    private function getFormErrors(Form $form)
    {
        $errors = array();
        foreach ($form as $child) {
            /** @var \Symfony\Component\Form\Form $child * */
            if (!$child->isValid()) {
                /** @var \Symfony\Component\Form\FormError $error */
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}