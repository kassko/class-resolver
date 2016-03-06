<?php

namespace Kassko\ClassResolver;

use Kassko\ClassResolver\ContainerInterface;
use LogicException;

/**
 * Class resolver witch wrap a container.
 *
 * @author kko
 */
class ContainerAwareClassResolver extends AbstractContainerClassResolver
{
    protected $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($className)
    {
        if (! $this->container->has($serviceId = $this->getServiceId($className))) {
            throw new LogicException(
                sprintf(
                    "The service \"%s\" cannot be found.", $serviceId
                )
            );
        }

        return $this->container->get($serviceId);
    }
}
