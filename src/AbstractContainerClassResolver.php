<?php

namespace Kassko\ClassResolver;

use Kassko\ClassResolver\Exception\NotResolvedClassException;
use LogicException;

/**
 * Base for class resolver witch wrap dependency container.
 *
 * @author kko
 */
abstract class AbstractContainerClassResolver implements ClassResolverInterface
{
    private $classNameToServiceId;

    public function __construct()
    {
        $this->classNameToServiceId = [];
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

    abstract public function resolve($className);

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
        if ('@' === $className[0]) {//Class name is a service if it starts by the char '#'.
            return substr($className, 1);
        }

        $className = trim($className, '\\');

        if (! isset($this->classNameToServiceId[$className])) {
            throw new NotResolvedClassException($className);
        }

        return $this->classNameToServiceId[$className];
    }
}
