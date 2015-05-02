<?php

namespace Managlea\Component\ResourceHandler;


use Managlea\Component\ResourceHandler;

class DoctrineResourceHandler extends ResourceHandler
{
    /**
     * @param int $resourceId
     * @todo Implement findResource() method.
     */
    protected function findResource($resourceId)
    {
        throw new \Exception('Method not implemented');
    }

    /**
     * @param array $filters
     * @TODO: Implement findResourceCollection() method.
     */
    protected function findResourceCollection(array $filters = array())
    {
        throw new \Exception('Method not implemented');
    }

    /**
     * @param array $data
     * @TODO: Implement createResource() method.
     */
    protected function createResource(array $data)
    {
        throw new \Exception('Method not implemented');
    }

    /**
     * @param int $resourceId
     * @param array $data
     * @TODO: Implement updateResource() method.
     */
    protected function updateResource($resourceId, array $data)
    {
        throw new \Exception('Method not implemented');
    }

    /**
     * @param int $resourceId
     * @TODO: Implement removeResource() method.
     */
    protected function removeResource($resourceId)
    {
        throw new \Exception('Method not implemented');
    }
}