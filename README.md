spry-php/loader
===

[![Buildi Status](https://travis-ci.org/spry-php/loader.png?branch=master)](https://travis-ci.org/spry-php/loader)

A simple autoloading library adhering to PSR-0.

Usage
---

First of all include the library and instantiate it.

```php
<?php

include '/path/to/vendor/library/Spry/Loader/Autoloader.php';
$autoloader = new Spry\Loader\Autoloader;
```

The best way to use the autoloader for performance reasons is to provide it with a class map.

```php
$classMap = [
    'Spry\Router\Routing'  => '/path/to/Spry/Router/Routing.php',
    'Spry\Router\RouteMap' => '/path/to/Spry/Router/RouteMap.php'
];

$autoloader->registerClasses($classMap)
           ->register();

$routing  = new Spry\Router\Routing;
$routeMap = new Spry\Router\RouteMap;
```

Another option would be to set paths to namespaces and/or prefixes.

```php
$namespaces = [
    'Spry' => '/path/to/Spry',
    'Zend' => '/path/to/ZendFramework'
];

$autoloader->registerNamespaces($namespaces)
           ->register();

$routing     = new Spry\Router\Routing;
$httpRequest = new Zend\Http\Request; // e.g. ZF2 namespaced components
```

or

```php
$prefixes = [
    'Zend_' => '/path/to/ZendFramework'
];

$autoloader->registerPrefixes($prefixes)
           ->register();

$httpRequest = new Zend_Http_Request; // e.g ZF1 prefixed components
```