<?php

namespace Kassko\ClassResolver;

/**
 * @deprecated Use ServiceAdapterClassResolver or StaticClassAdapterClassResolver instead
 *
 * Contract for factory witch create object from a class name.
 *
 * @see ServiceAdapterClassResolver
 * @see StaticClassAdapterClassResolver
 *
 * @author kko
 */
interface ClassFactoryProviderInterface
{
    function support($className);
    function getInstance($className);
}
