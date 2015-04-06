<?php

namespace Managlea\Component;

abstract class ResourceHandler implements ResourceHandlerInterface
{
    protected $resourceFactory = null;

    /**
     * @param int $resourceId
     */
    abstract protected function findResource($resourceId);

    /**
     * @param array $filters
     */
    abstract protected function findResourceCollection(array $filters = array());

    /**
     * @param array $data
     */
    abstract protected function createResource(array $data);

    /**
     * @param int $resourceId
     * @param array $data
     */
    abstract protected function updateResource($resourceId, array $data);

    /**
     * @param int $resourceId
     */
    abstract protected function removeResource($resourceId);

    /**
     * @param ResourceFactoryInterface $resourceFactory
     */
    public function __construct(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @param int $resourceId
     * @return mixed
     */
    final public function getSingle($resourceId)
    {
        $resource = $this->findResource($resourceId);

        if (!$resource) {
            return false;
        }

        return $resource;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    final public function getCollection(array $filters = array())
    {
        $collection = $this->findResourceCollection($filters);

        return $collection;
    }

    /**
     * @param array $data
     * @return mixed
     */
    final public function postSingle(array $data)
    {
        $resource = $this->createResource($data);

        return $resource;
    }

    /**
     * @param int $resourceId
     * @param array $data
     * @return mixed
     */
    final public function putSingle($resourceId, array $data)
    {
        $resource = $this->findResource($resourceId);

        if (!$resource) {
            return false;
        }

        $updatedResource = $this->updateResource($resourceId, $data);

        return $updatedResource;
    }

    /**
     * @param int $resourceId
     * @return bool
     */
    final public function deleteSingle($resourceId)
    {
        $resource = $this->findResource($resourceId);

        if (!$resource) {
            return false;
        }

        $this->removeResource($resourceId);

        return true;
    }
} 