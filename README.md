# Brunt

Brunt is a simple but powerful dependency injection framework. 
Since php7 reflection can be used to analyze constructors properly.

## Usage

### Composer

```json
  "require": {
    "sysvyz/brunt": "dev-master",
  },
```


### Test

Unit tests in ``/test/``

``phpunit --bootstrap fileloader.php test``


### Include

simply use ``composer dump-autoload``

#### Example 1: basic usage
```php

//1 Create root-injector
$injector = new Injector(null);

//2 Define providers
//                          TOKEN           PROVIDER            CLASS
$injector->addProviders([Engine::class => ClassProvider::init(Engine::class)]);

//3 Get instance
/** @var Engine $engine */
$engine = $injector->get(Engine::class);
```


#### Example 2: magic get

```php
$engine = $injector->get(Engine::class);
//is equivalent to
$engine = $injector->{Engine::class};
```

#### Example 3: Binding

Bindings are a more convenient way to define Providers 

```
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

#### Example Repo:

[working example using brunt](https://github.com/sysvyz/brunt-example)



