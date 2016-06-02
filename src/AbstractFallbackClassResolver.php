<?php

namespace Kassko\ClassResolver;

/**
 * Base for fallbacks class resolvers. 
 *
 * A fallback class resolver is a resolver used in the chain class resolver as fallback
 * when no class resolver can resolve a class.
 *
 * @author kko
 */
abstract class AbstractFallbackClassResolver implements ClassResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function support($className)
    {
        return true;
    }
}
