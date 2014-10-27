<?php

namespace Kassko\ClassResolver;

use Closure;

/**
 * Class resolver witch allow to work with a factory closure.
 *
 * @author kko
 */
class ClosureClassResolver implements ClassResolverInterface
{
    private $factoryClosure;

    public function __construct(Closure $factoryClosure = null)
    {
        $this->factoryClosure = $factoryClosure;
    }

    public function set(Closure $factoryClosure)
    {
        $this->factoryClosure = $factoryClosure;

        return $this;
    }

    public function resolve($className)
    {
        $this->factoryClosure->__invoke($className);
    }
}