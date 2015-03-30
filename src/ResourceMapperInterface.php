<?php

namespace Managlea\ResourceMapper;


interface ResourceMapperInterface
{
    /**
     * @param int $resourceId
     * @return mixed
     */
    public function getSingle($resourceId);

    /**
     * @param array $filters
     * @return \Exception
     */
    public function getCollection(array $filters);

    //public function getRelation();

    /**
     * @param array $data
     * @return mixed
     */
    public function postSingle(array $data);

    /**
     * @param int $resourceId
     * @param array $data
     * @return mixed
     */
    public function putSingle($resourceId, array $data);

    /**
     * @param int $resourceId
     * @return mixed
     */
    public function deleteSingle($resourceId);
}
