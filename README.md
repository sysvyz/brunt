# Prescription

Prescription is a simple but powerful dependency injection framework. 
Since php7 reflection can be used to analyze constructors properly.

## Usage

### Composer

```
  "require": {
    "sysvyz/prescription": "dev-master",
  },
```


### Test

Unit tests in ``/test/``

``phpunit --bootstrap fileloader.php test``


### Include

``composer dump-autoload``
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

### Providers

Each Injectable has a list of providers.
Each Injector has a list of providers.
If an Injector creates an Injectable, it gets responsible for all of it's providers

#### Example:
```
RootInjector provides Components A and C
ComponentA depends on Component B and C
ComponentA provides Component C

ComponentB depends on Component C
ComponentB provides Component C

RootInjector
└─ComponentA(1)
  ├─ComponentB(1)      
  │ └─ComponentC(1)
  └ComponentC(2)
```
As illustrated above, A's Component C is an other Instance as B's Component C, because A and B provide their own Cs.

