<?php

namespace Kassko\ClassResolver;

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

    public function resolve($className)
    {
        $this->factoryCallable->__invoke($className);
    }
}