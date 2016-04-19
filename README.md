# Prescription

Prescription is a simple but powerful dependency injection framework. 
Since php7 reflection can be used to analyze constructors properly.

## Usage

### Test

``phpunit --bootstrap fileloader.php test``


### Include

``include_once "fileloader.php";`` or use some other autoloader, 
this library should be independent from any autoloading process

### Providers


Each Injectable has a list of providers.
Each Injector has a list of providers.
If an Injector creates an Injectable, it gets responsible for all of it's providers

Example:
```
RootInjector provides Component A and C
ComponentA depends on Component B C
ComponentA provides Component C

ComponentB depends on Component C
ComponentB provides Component C

RootInjector
└─ComponentA(1)
  ├─ComponentB(1)      
  │ └─ComponentC(1)
  └ComponentC(2)
```
As illustrated above, A's Component C is an other Instance as B's Component C. This is because A and B Provide their own Cs.

