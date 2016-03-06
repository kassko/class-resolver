<?php

namespace Kassko\ClassResolver;

/**
 * Contract for class resolvers.
 *
 * @author kko
 */
interface ClassResolverInterface
{
    function support($className);
    function resolve($className);
}
