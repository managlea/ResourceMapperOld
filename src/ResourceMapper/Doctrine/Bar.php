<?php
namespace Managlea\TestingBundle\Utility\ResourceMapper\Doctrine;

use Managlea\CoreBundle\Utility\ResourceMapper\Doctrine;

class Bar extends Doctrine
{
    protected $sourceObjectNamespace = 'Managlea\TestingBundle\Entity\Bar';
    protected $sourceObjectFormNamespace = 'Managlea\TestingBundle\Form\Type\BarType';
}