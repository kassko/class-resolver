<?php

namespace Kassko\ClassResolver;

/**
 * Class resolver which wrap a static factory.
 *
 * @author kko
 */
class StaticFactoryAdapterClassResolver implements ClassResolverInterface
{
    private $class;
    private $supportsMethod;
    private $resolveMethod;

    public function __construct($class = null, $supportsMethod = null, $resolveMethod = false)
    {
        $this->class = $class;
        $this->supportsMethod = $supportsMethod;
        $this->resolveMethod = $resolveMethod;
    }

    public function set($class, $supportsMethod, $resolveMethod)
    {
        $this->class = $class;
        $this->supportsMethod = $supportsMethod;
        $this->resolveMethod = $resolveMethod;

        return $this;
    }

    public function support($className)
    {
        return call_user_func_array([$this->class, $this->supportsMethod], [$className]);
    }

    public function resolve($className)
    {
        return call_user_func_array([$this->class, $this->resolveMethod], [$className]);
    }
}
