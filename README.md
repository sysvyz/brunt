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

Each Injectable has a list of providers
