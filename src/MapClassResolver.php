<?php

namespace Kassko\ClassResolver;

use Closure;

/**
 * Class resolver which wrap a map containing pairs as "classOrKey => service"
 * or as "classOrKey => callable" if the service need to be lazyloaded.
 *
 * @author kko
 */
class MapClassResolver implements ClassResolverInterface
{
    private $map;

    public function __construct(array $map = null)
    {
        $this->map = $map; 
    }

    public function setMap(array $map)
    {
        $this->map = $map; 

        return $this;
    }

    public function addPair($classOrKey, $object)
    {
        $this->map[$classOrKey] = $object; 

        return $this;
    }

    public function addMap(array $map)
    {
        $this->map = $this->map + $map;

        return $this;
    }

    public function support($className)
    {
        return isset($this->map[$className]) || array_key_exists($className, $this->map);
    }

    public function resolve($className)
    {
        if ($this->map[$className] instanceof Closure) {
            return $this->map[$className] = $this->map[$className]();
        }

        if (is_callable($this->map[$className])) {
            return $this->map[$className] = call_user_func($this->map[$className]);
        }

        return $this->map[$className];
    }
}
