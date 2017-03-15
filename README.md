# Brunt

Brunt is a simple but powerful dependency injection framework. 
Since php7, reflection can be used to analyze constructors properly.

## Usage

### Composer

```json
  "require": {
    "sysvyz/brunt": "1.0.*",
  },
```


### Test

Unit tests in ``/test/``

``phpunit --bootstrap fileloader.php test``


### Include

simply use ``composer dump-autoload``


### Examples

#### Example 1: basic usage
```php

$injector = new Brunt\Injector(null);

/** @var Engine $engine */
$engine = $injector->get(Engine::class);

```


#### Example 2: magic get

```php

$engine = $injector->get(Engine::class);
//is equivalent to
$engine = $injector->{Engine::class};
```

#### Example 3: define providers

```php

$injector = new Injector(null);

//                          TOKEN           PROVIDER            CLASS
$injector->addProviders([Engine::class => ClassProvider::init(HeavyEngine::class)]);

/** @var Engine $engine */
$engine = $injector->get(Engine::class);
```


#### Example 4: Binding

Bindings are a more convenient way to define Providers 

```php
$injector->bind([

    bind('%SomeValue%')
    ->toValue(3.1415),
    
    bind(Car::class)
    ->toClass(Car::class),
    
    bind(Request::class)
        ->toFactory(function (Injector $injector) {
            return Request::createFromGlobals();
        })
])

```

#### Example 5: Singleton

Just call ``singleton()`` and the provider always returns the same object.


```php
$injector = new Injector(null);

//                          TOKEN           PROVIDER            CLASS              SINGLETON
$injector->addProviders([Engine::class => ClassProvider::init(HeavyEngine::class)->singleton()]);

/** @var Engine $engine */
$engine = $injector->get(Engine::class);
```

or as binding

```php
$injector = new Injector(null);
$injector->bind([
    
    bind(Car::class)
    ->toClass(Car::class)->singleton(),
    
]);
$car = $injector->get(Car::class)
```


#### Example 6: Lazy

Just call ``lazy()`` and the provider returns a proxy object the real object will be created on first use.


```php
$injector = new Injector(null);

//                          TOKEN           PROVIDER            CLASS              LAZY
$injector->addProviders([Engine::class => ClassProvider::init(HeavyEngine::class)->lazy()]);

/** @var Engine $engine */
$engine = $injector->get(Engine::class); //returns a proxy object
```

or as binding

```php
$injector = new Injector(null);
$injector->bind([
    
    bind(Car::class)
    ->toClass(Car::class)->lazy(),
    
]);
$car = $injector->get(Car::class) //returns a proxy object 

$car->honk() //creates the actual car and honks
```

the proxy object inherits from the actual class, so it can be used as if it was the object it passes instanceof and function parameter type declarations


#### Example 7: Lazy and Singleton

combine lazy and singleton (order doesn't matter)

```php

bind(Car::class)->lazy()->singleton()
```

```php
ClassProvider::init(Car::class)->lazy()->singleton();

```


#### Example 7: Alias

... alias

```php
$injector->addProviders([
    HeavyEngine::class => ClassProvider::init(HeavyEngine::class)->lazy()
    Engine::class => AliasProvider::init(HeavyEngine::class)
]);
$heavyEngine = $injector->get(Engine::class); //returns a proxy object for HeavyEngine

```

#### Example 7: Hierarchy

coming up...

#### Example Repo:

[working example using brunt](https://github.com/sysvyz/brunt-example)



