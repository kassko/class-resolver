<?php

namespace Kassko\ClassResolver;

/**
 * Contract for factory witch create object from a class name.
 *
 * @author kko
 */
interface ClassFactoryProviderInterface
{
	function support($className);
	function getInstance($className);
}