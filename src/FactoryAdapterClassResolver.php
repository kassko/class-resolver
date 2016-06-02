<?php

namespace Kassko\ClassResolver;

/**
 * Class resolver which wrap a factory.
 *
 * @author kko
 */
class FactoryAdapterClassResolver implements ClassResolverInterface
{
    private $factory;
    private $supportsMethod;
    private $resolveMethod;

    public function __construct($factory = null, $supportsMethod = null, $resolveMethod = false)
    {
        $this->factory = $factory;
        $this->supportsMethod = $supportsMethod;
        $this->resolveMethod = $resolveMethod;
    }

    public function set($factory, $supportsMethod, $resolveMethod)
    {
        $this->factory = $factory;
        $this->supportsMethod = $supportsMethod;
        $this->resolveMethod = $resolveMethod;

        return $this;
    }

    public function support($className)
    {
        return $this->factory->{$this->supportsMethod}($className);
    }

    public function resolve($className)
    {
        return $this->factory->{$this->$resolveMethod}($className);
    }
}
