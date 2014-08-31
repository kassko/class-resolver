<?php

namespace KKO\ClassResolver;

/**
 * This class resolver is used in the chain class resolver as fallback
 * when no class resolver can resolve a class.
 *
 * @author kko
 */
class DefaultClassResolver implements ClassResolverInterface
{
	public function support($className)
	{
		return true;
	}

	public function resolve($className)
	{
		return new $className;
	}
}