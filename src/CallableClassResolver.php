<?php

namespace Kassko\ClassResolver;

/**
 * @deprecated Use FactoryAdapterClassResolver or StaticFactoryAdapterClassResolver instead
 * 
 * Class resolver witch allows to work with a factory callable.
 *
 * @see ServiceAdapterClassResolver
 * @see StaticClassAdapterClassResolver
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
        return call_user_func_array($this->factoryCallable, [$className]);
    }
}
