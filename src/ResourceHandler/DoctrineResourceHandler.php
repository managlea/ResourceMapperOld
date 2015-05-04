<?php

namespace Managlea\Component\ResourceHandler;


use Doctrine\ORM\EntityRepository;
use Managlea\Component\EntityManagerInterface;
use Managlea\Component\ResourceHandler;
use Managlea\Component\ResourceFactoryInterface;

class DoctrineResourceHandler extends ResourceHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ResourceFactoryInterface $resourceFactory, EntityManagerInterface $entityManager)
    {
        parent::__construct($resourceFactory);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $resourceId
     * @return bool
     * @throws \Exception
     */
    protected function findResource($resourceId)
    {
        $repository = $this->entityManager->getRepository(/*static::SOURCE_OBJECT_NAMESPACE*/);

        if (!($repository instanceof EntityRepository)) {
            throw new \Exception('Repository not found');
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
     * @throws \Exception
     */
    protected function findResourceCollection(array $filters = array())
    {
        $limit = 20;
        $offset = 0;

        $repository = $this->entityManager->getRepository(/*static::SOURCE_OBJECT_NAMESPACE*/);

        if (!($repository instanceof EntityRepository)) {
            throw new \Exception('Repository not found');
        }

        $collection = $repository->findBy($filters, null, $limit, $offset);

        return $collection;
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