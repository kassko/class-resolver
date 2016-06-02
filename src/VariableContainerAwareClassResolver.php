<?php

namespace Kassko\ClassResolver;

use LogicException;

/**
* Class resolver witch wrap a configurable container.
 *
 * @author kko
 */
class VariableContainerAwareClassResolver extends AbstractContainerClassResolver
{
    protected $container;
    private $hasMethod; 
    private $getMethod;

    public function setContainer($container, $hasMethod, $getMethod)
    {
        $this->container = $container;
        $this->hasMethod = $hasMethod; 
        $this->getMethod = $getMethod;
    }

    public function resolve($className)
    {
        if (! $this->container->{$this->hasMethod}($serviceId = $this->getServiceId($className))) {
            throw new LogicException(
                sprintf(
                    "The service \"%s\" cannot be found.", $serviceId
                )
            );
        }

        return $this->container->{$this->getMethod}($serviceId);
    }
}
