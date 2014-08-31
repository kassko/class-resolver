Problématique traitée
---------------

On construit un objet à partir de son nom de classe avec un new:

```php
<?php

class ClientClass
{
    private $config;

    public function clientFunction()
    {
        //...
        $cacheClass = $config->getCacheClass();
        $myCacheInstance = new $cacheClass();
        //...
    }
}

```

Mais cela ne convient pas si l'objet est complexe à construire.
Ci-dessous, un objet est construit à partir de son nom de classe.

```php
<?php

use Kassko\ClassResolver\ClassResolverInterface;

class ClientClass
{
    private $config;
    private $classResolver;

    public function __construct(ClassResolverInterface $classResolver)
    {
        $this->classResolver = $classResolver;
    }

    public function clientFunction()
    {
        $cacheClass = $this->config->getCacheClass();
        $myCacheInstance = $this->classResolver->resolve($cacheClass);
        //Exploiter $myCacheInstance
    }
}

```

Si la classe ne peut pas être résolue, l'objet est crée avec "new $cacheClass".
Mais il est possible de vérifier au préalable si l'objet pourra être construit.
Cela permet de gérer soi-même le comportement alternatif à adopter quand l'objet ne peut être
construit à partir de son nom de classe:

```php
<?php

use Kassko\ClassResolver\ClassResolverInterface;

class ClientClass
{
    private $classResolver;
    private $config;

    public function __construct(ClassResolverInterface $classResolver, $config)
    {
        $this->classResolver = $classResolver;
        $this->config = $config;
    }

    public function clientFunction()
    {
        $cacheClass = $this->config->getCacheClass();
        if ($this->classResolver->support($cacheClass)) {
            $myCacheInstance = $this->classResolver->resolve($cacheClass);
            //Exploiter $myCacheInstance
        } else {
            throw new \LogicException("Blah blah blah.");
        }
    }
}

```

Exemple d'utilisation
-------------------

```php
use Kassko\ClassResolver\ClassResolverChain;
use Kassko\ClassResolver\FactoryClassResolver;

$classResolver = new ClassResolverChain(
    (new FactoryClassResolver([new CacheFactoryProvider()]))
);
```

```php
use Kassko\ClassResolver\ClassFactoryProviderInterface;

class CacheFactoryProvider implements ClassFactoryProviderInterface
{
    public function support($className)
    {
        return 'CouchbaseCache' === $className || 'RiakCache' === $className;
    }

    public function getInstance($className)
    {
        switch($className)
        {
            case 'CouchbaseCache':

                $cache = new CouchBaseCache;
                $cache->setHost('127.0.0.1');
                $cache->setLogin('login');
                $cache->setPassword('mp');
                break;

            case 'RiakCache':
                $cache = new RiakCache;
                $cache->setHost('127.0.0.1');
                $cache->setLogin('login');
                $cache->setPassword('mp');
                break;
        }

        return null;
    }
}
```

Il est possible d'utiliser un conteneur en lieu et place de la factory.
A l'heure actuelle, il n'existe pas d'interface standard pour les conteneurs de dépendances.
L'interface crée par Symfony a été choisie.

```php
use Kassko\ClassResolver\ClassResolverChain;
use Kassko\ClassResolver\FactoryClassResolver;

//Ici du code qui crée $container, une instance de Symfony\Component\DependencyInjection\ContainerInterface

$classResolver = new ClassResolverChain(
    (new ContainerAwareClassResolver([$container]))
);
```

Il existe un résolveur de repli quand il n'a pas été possible de crée l'objet à partir du nom de classe.
Celui-ci crée construit l'objet avec un new $className.

Mais il est possible de changer ce résolveur si par exemple vous préférez qu'une exception soit levée quand l'objet n'a pu être crée.

```php
class MyDefaultClassResolver implements ClassResolverInterface
{
    public function support($className)
    {
        return true;
    }

    public function resolve($className)
    {
        throw new SomeException(sprintf('Cannot create object from given class name "%s"', $className));
    }
}
```

```php
use Kassko\ClassResolver\ClassResolverChain;
use Kassko\ClassResolver\FactoryClassResolver;

$classResolver = new ClassResolverChain(
    (new FactoryClassResolver([new CacheFactoryProvider(), new MyDefaultClassResolver]))
);

```
