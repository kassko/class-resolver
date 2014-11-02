<?php

namespace Kassko\ClassResolver;

use Kassko\Common\DependencyInjection\ContainerInterface;
use Kassko\ClassResolver\Exception\NotResolvedClassException;
use LogicException;

/**
 * Class resolver wicth allow to work with a dependency container.
 *
 * @author kko
 */
class ContainerAwareClassResolver implements ClassResolverInterface
{
    protected $classNameToServiceId;
    protected $container;

    public function __construct()
    {
        $this->classNameToServiceId = [];
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function support($className)
    {
        try {
            $this->getServiceId($className);
        } catch (NotResolvedClassException $e) {
            return false;
        }

        return true;
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

    public function registerClass($className, $service)
    {
        $className = trim($className, '\\');

        if (array_key_exists($className, $this->classNameToServiceId)) {
            throw new LogicException(
                sprintf(
                    "A mapping already exists for class %s."
                    ." Service to map \"%s\"."
                    ." Service already mapped: \"%s\".",
                    $service,
                    $this->classNameToServiceId[$className]
                )
            );
        }

        $this->classNameToServiceId[$className] = $service;
    }

    protected function getServiceId($className)
    {
        $className = trim($className, '\\');

        if (! isset($this->classNameToServiceId[$className])) {
            throw new NotResolvedClassException($className);
        }

        return $this->classNameToServiceId[$className];
    }
}