<?php

namespace Kassko\ClassResolver;

/**
 * A trait for user of class resolver for injecting it.
 *
 * @author kko
 */
trait ClassResolverAwareTrait
{
    
    /**
     * @var ClassResolverInterface 
     */
    private $classResolver;

    /**
     * @param ClassResolverInterface $classResolver
     *
     * @return
     */
    public function setClassResolver(ClassResolverInterface $classResolver)
    {
        $this->classResolver = $classResolver;

        return $this;
    }
}
