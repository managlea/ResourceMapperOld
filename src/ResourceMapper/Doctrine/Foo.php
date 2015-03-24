<?php
namespace Managlea\TestingBundle\Utility\ResourceMapper\Doctrine;
use Managlea\CoreBundle\Utility\ResourceMapper\Doctrine as DoctrineResourceMapper;
class Foo extends DoctrineResourceMapper
{
    const SOURCE_OBJECT_NAMESPACE = 'Managlea\TestingBundle\Entity\Foo';
    const SOURCE_OBJECT_FORM_NAMESPACE = 'Managlea\TestingBundle\Form\Type\FooType';
    /**
     * @param $entity
     * @return array
     */
    protected static function mapData($entity)
    {
        $data = array(
            'id' => $entity->getId(),
            'first_name' => $entity->getName()
        );
        return $data;
    }
}