<?php

namespace Managlea\ResourceHandler;

interface ResourceHandlerInterface
{
    /**
     * @param int $resourceId
     */
    public function getSingle($resourceId);

    /**
     * @param array $filters
     */
    public function getCollection(array $filters = array());

    /**
     * @param array $data
     */
    public function postSingle(array $data);

    /**
     * @param int $resourceId
     * @param array $data
     */
    public function putSingle($resourceId, array $data);

    /**
     * @param int $resourceId
     */
    public function deleteSingle($resourceId);
}
