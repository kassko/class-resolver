<?php

namespace Kassko\ClassResolver;

use Kassko\ClassResolver\ClassResolverInterface;

/**
 * A single class resolver that hide the usage of multiples.
 * Loop on all class resolvers until one can resolve the class name.
 *
 * @author kko
 */
class ClassResolverChain implements ClassResolverInterface
{
    protected $resolvers;
    protected $defaultResolver;

    public function __construct(array $resolverCollection = [], ClassResolverInterface $defaultResolver = null)
    {
        $this->resolvers = $resolverCollection;
    }

    public function set(array $resolverCollection)
    {
        $this->resolvers = $resolverCollection;

        return $this;
    }

    public function add(ClassResolverInterface $classResolver)
    {
        $this->resolvers[] = $classResolver;

        return $this;
    }

    public function addCollection(array $resolverCollection, $prepend = false)
    {
        if (false === $prepend) {
            array_merge($this->resolvers, $resolverCollection);
        } else {
            array_merge($resolverCollection, $this->resolvers);
        }

        return $this;
    }

    public function setDefault(ClassResolverInterface $defaultResolver)
    {
        $this->defaultResolver = $defaultResolver;

        return $this;
    }

    public function support($className)
    {
        //The ClassResolverChain has a defaultResolver,
        //then it can fall back on it, and then it supports all class names.
        return true;
    }

    public function resolve($className)
    {
        $resolver = $this->findResolver($className);

        return $resolver->resolve($className);
    }

    protected function findResolver($className)
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->support($className)) {
                return $resolver;
            }
        }

        return $this->defaultResolver ?: $this->defaultResolver = new DefaultClassResolver;
    }
}
