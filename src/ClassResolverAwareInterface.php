<?php

namespace Kassko\ClassResolver;

/**
 * A contract for users of class resolver.
 *
 * @author kko
 */
interface ClassResolverAwareInterface
{
    /**
     * @param ClassResolverInterface $classResolver
     *
     * @return self
     */
    public function setClassResolver(ClassResolverInterface $classResolver);
}
