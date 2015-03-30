<?php

namespace Managlea\CoreBundle\Utility\ResourceMapper;

use Managlea\CoreBundle\Utility\ResourceMapper;
use Managlea\CoreBundle\Utility\RDBMS;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


abstract class Doctrine extends ResourceMapper
{
    /**
     * @var \Doctrine\ORM\EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @param RDBMS $rdbms
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    final public function setRDBMS(RDBMS $rdbms)
    {
        $entityManager = $rdbms->getOrm();

        if (!$entityManager instanceof EntityManager) {
            throw new Exception('Entity manager not instance of \Doctrine\ORM\EntityManager');
        }

        $this->entityManager = $entityManager;
    }

    /**
     * @param $resourceId
     * @return bool|object
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    final protected function findEntity($resourceId)
    {
        if (!$this->entityManager) {
            throw new Exception('Entity manager not set');
        }

        $repository = $this->entityManager->getRepository(static::SOURCE_OBJECT_NAMESPACE);

        if (!($repository instanceof EntityRepository)) {
            throw new Exception('Repository not found');
        }

        $entity = $repository->find($resourceId);

        if (!$entity) {
            return false;
        }

        return $entity;
    }

    /**
     * @param array $filters
     * @return array
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    final protected function findEntityCollection(array $filters)
    {
        $limit = 20;
        $offset = 0;

        if (!$this->entityManager) {
            throw new Exception('Entity manager not set');
        }

        $repository = $this->entityManager->getRepository(static::SOURCE_OBJECT_NAMESPACE);

        if (!($repository instanceof EntityRepository)) {
            throw new Exception('Repository not found');
        }

        $collection = $repository->findBy($filters, null, $limit, $offset);

        return $collection;
    }

    /**
     * @param $entity
     * @return mixed
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public static function getMappedData($entity)
    {
        $namespace = static::SOURCE_OBJECT_NAMESPACE;

        if (!$entity instanceof $namespace) {
            throw new Exception('Entity is not instance of ' . $namespace);
        }

        return static::mapData($entity);
    }

    /**
     * @param $entity
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    protected function persistEntity($entity)
    {
        if (!$this->entityManager) {
            throw new Exception('Entity manager not set');
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param $entity
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    protected function removeEntity($entity)
    {
        /**
         * @todo is this check needed?
         */
//    if (!$this->entityManager)
//    {
//      throw new Exception('Entity manager not set');
//    }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}