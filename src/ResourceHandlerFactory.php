<?php

namespace Managlea\Component;

class ResourceHandlerFactory
{
    /**
     * @param string $resourceName
     * @return ResourceHandlerInterface
     */
    public static function create($resourceName)
    {
        $class = $resourceName . 'Handler';

        //return new ResourceHandler\DoctrineResourceHandler();

        return new $class();
    }
} 