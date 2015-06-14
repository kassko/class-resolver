<?php

namespace Kassko\ClassResolver;

use Kassko\ClassResolver\Exception\NotResolvedClassException;

/**
 * Class resolver witch allow to work with a factory callable.
 *
 * @author kko
 */
class CallableClassResolver implements ClassResolverInterface
{
    private $factoryCallable;

    public function __construct(callable $factoryCallable = null)
    {
        $this->factoryCallable = $factoryCallable;
    }

    public function set(callable $factoryCallable)
    {
        $this->factoryCallable = $factoryCallable;

        return $this;
    }

    public function support($className)
    {
        return true;
    }

    public function resolve($className)
    {
        return $this->factoryCallable->__invoke($className);
    }
}