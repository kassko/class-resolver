<?php

namespace Kassko\ClassResolver;

use Closure;

/**
 * Class resolver which wrap a closure.
 *
 * @author kko
 */
class ClosureClassResolver implements ClassResolverInterface
{
    private $closure;

    public function __construct(Closure $closure = null)
    {
        $this->closure = $closure;
    }

    public function set(Closure $closure)
    {
        $this->closure = $closure;

        return $this;
    }

    public function support($className)
    {
        return true;
    }

    public function resolve($className)
    {
        return $this->closure->__invoke($className);
    }
}
