class-resolver
==================

[![Latest Stable Version](https://poser.pugx.org/kassko/class-resolver/v/stable.png)](https://packagist.org/packages/kassko/class-resolver)
[![Total Downloads](https://poser.pugx.org/kassko/class-resolver/downloads.png)](https://packagist.org/packages/kassko/class-resolver)
[![Latest Unstable Version](https://poser.pugx.org/kassko/class-resolver/v/unstable.png)](https://packagist.org/packages/kassko/class-resolver)

Allows to create an object with dependencies from it's class name.

## Installation

You can install this component with composer
`composer require kassko/class-resolver:some_version`

## Usage

### Use the class-resolver

Imagine, you need to create a cache instance and you have only the cache class name.

In the simpliest case, you can go for such code:
```php
class ClientClass
{
    /**
     * @var string
     */
    private $cacheClass;
    
    public function __construct($cacheClass)
    {
        $this->cacheClass = $cacheClass;
    }

    public function clientFunction()
    {
        //...
        $myCacheInstance = new $this->cacheClass;
        //...
    }
}
```

If the cache class has some dependencies, you can use the class-resolver and go through this kind of code:
```php
use Kassko\ClassResolver\ClassResolverInterface;

class ClientClass
{
    /**
     * @var string
     */
    private $cacheClass;
    /**
     * @var ClassResolverInterface $classResolver
     */
    private $classResolver;

    public function __construct($cacheClass, ClassResolverInterface $classResolver)
    {
        $this->cacheClass = $cacheClass;
        $this->classResolver = $classResolver;
    }

    public function clientFunction()
    {
        //...
        $myCacheInstance = $this->classResolver->resolve($this->cacheClass);
        //...
    }
}
```

If you need to handle yourself the case when the class cannot be resolved with the class-resolver, you can go through such code:
```php
use Kassko\ClassResolver\ClassResolverInterface;

class ClientClass
{
    /**
     * @var string
     */
    private $cacheClass;
    /**
     * @var ClassResolverInterface $classResolver
     */
    private $classResolver;

    public function __construct($cacheClass, ClassResolverInterface $classResolver)
    {
        $this->cacheClass = $cacheClass;
        $this->classResolver = $classResolver;
    }

    public function clientFunction()
    {
        //...
        if ($this->classResolver->support($this->cacheClass)) {
            $myCacheInstance = $this->classResolver->resolve($this->cacheClass);
        } else {
            //Here, the code that handle the case when the class cannot be resolved.
        }
        //...
    }
}
```

### Feed the class-resolver

There are severals ways to feed the class-resolver. Each way of feeding has it's own class-resolver implementation.

#### From a map [`Kassko\ClassResolver\MapClassResolver`](src/MapClassResolver.php)

```php
use Kassko\ClassResolver\MapClassResolver;

$mapClassResolver = new MapClassResolver(['Kassko\Sample\CacheClass' => $cacheInstance]);
```

You can use a closure for lazy loading:

```php
use Kassko\ClassResolver\MapClassResolver;

$mapClassResolver = new MapClassResolver(['Kassko\Sample\CacheClass' => function () use ($cacheInstance) {return new Kassko\Sample\CacheClass(new Kassko\Sample\Dependency);};
```

#### From a container by providing the `get` method, and the `has` method.

```php
$classResolver = new VariableContainerAwareClassResolver($container, 'get', 'has');
```

The `get` method is the method that resolves the service and the `has` method is the method that checks if the service exists.


#### From a container adapter that implements [`Kassko\ClassResolver\ContainerInterface`](src/ContainerInterface.php)

```php
$container = new MyContainerAdapter($container);
$classResolver = new ContainerAwareClassResolver($container);
```

You can find an adapter in the bundle class-resolver-bundle. This is [`an adapter for Symfony container`](https://github.com/kassko/class-resolver-bundle/blob/master/src/Adapter/Container/SymfonyContainerAdapter.php).

#### From a closure 

```php
$container = new MyContainerAdapter($container);
$cacheInstance = new Kassko\Sample\CacheClass($someDependency);

$classResolver = new ClosureClassResolver(function ($className) {
    switch ($className) use ($cacheInstance) {
        case 'Kassko\Sample\CacheClass':
            return $cacheInstance;
    }
});
```

#### From a factory

```php
class CacheFactory
{
    public function supports($className) {return 'Kassko\Sample\CacheClass' === $className;} 
    public function get($className) {return new $className;} 
}

$factory = new FactoryAdapterClassResolver();
$classResolver = new ContainerAwareClassResolver($factory, 'supports', 'get');
```

#### From a static factory

```php
class CacheFactory
{
    public static function supports($className) {return 'Kassko\Sample\CacheClass' === $className;} 
    public static function get($className) {return new $className;} 
}

$classResolver = new ContainerAwareClassResolver('StaticFactoryAdapterClassResolver', 'supports', 'get');
```

#### From others class-resolver

```php
$classResolver = new ClassResolverChain();
$classResolver->add($mapClassResolver);
$classResolver->add($closureClassResolver);
```

If no resolver can handle the className, a basic `new $className` is performed.

You can change this behaviour so that an exception `Kassko\ClassResolver\NotResolvedClassException` is thrown instead.

```php
use Kassko\ClassResolver\DefaultClassResolver;

$classResolver = new ClassResolverChain();
$classResolver->add($mapClassResolver);
$classResolver->add($closureClassResolver);
$classResolver->setDefaultResolver(new DefaultClassResolver(['throw_exception']));
```

Or you can provide your own default resolver implementation to `setDefaultResolver()` method.
