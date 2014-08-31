<?php

namespace Kassko\ClassResolver;

use Kassko\ClassResolver\ClassFactoryProviderInterface;
use Kassko\ClassResolver\Exception\NotResolvedClassException;

/**
 * Class resolver to work with a factory.
 *
 * @author kko
 */
class FactoryClassResolver implements ClassResolverInterface
{
	private $factories;

	public function __construct(array $factories = [])
	{
		$this->factories = $factories;
	}

	public function set(array $factories)
	{
		$this->factories = $factories;

		return $this;
	}

	public function add(ClassFactoryProviderInterface $factory)
	{
		$this->factories[] = $factory;

		return $this;
	}

	public function addCollection(array $factories, $prepend = false)
	{
		if (false === $prepend) {
			array_merge($this->factories, $factories);
		} else {
			array_merge($factories, $this->factories);
		}

		return $this;
	}

	public function support($className)
	{
		try {
			$this->findFactory($className);
			return true;
		} catch (NotResolvedClassException $e) {
		}

		return false;
	}

	public function resolve($className)
	{
		$factory = $this->findFactory($className);

		return $factory->getInstance($className);
	}

	protected function findFactory($className)
	{
		foreach ($this->factories as $factory) {
			if ($factory->support($className)) {
				return $factory;
			}
		}

		throw new NotResolvedClassException($className);
	}
}