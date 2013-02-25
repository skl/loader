# Orno\Loader - PHP Autoloading Adhering to PSR-0

[![Buildi Status](https://travis-ci.org/orno/loader.png?branch=master)](https://travis-ci.org/orno/loader)

A simple autoloading library adhering to PSR-0.

### Usage

The best way to use the autoloader for performance reasons is to provide it with a class map. This means the aotoloader
knows the exact location of any files it requests.

    $classMap = [
        'Orno\Router\Routing'  => '/path/to/Orno/Router/Routing.php',
        'Orno\Router\RouteMap' => '/path/to/Orno/Router/RouteMap.php'
    ];

    (new Orno\Loader\Autoloader)
        ->registerClasses($classMap)
        ->register();

    $routing  = new Orno\Mvc\Route\Router;
    $routeMap = new Orno\Mvc\Route\Map;

The fallback for a classmap should always be set as a namespace or prefix path. For example, the location where a vendor namespace exists.

    $namespaces = [
        'Orno' => '/vendor/orno/src',
        'Zend' => '/vendor/zf2/library'
    ];

    (new Orno\Loader\Autoloader)
        ->registerNamespaces($namespaces)
        ->register();

    $routing     = new Orno\Mvc\Route\Router;
    $routeMap    = new Orno\Mvc\Route\Map;
    $httpRequest = new Zend\Http\Request; // e.g. ZF2 namespaced components

The autoloader also has a fallback to prefixed classes where an underscore would act in a similar way to a namespace/directory separator.

    $prefixes = [
        'Zend_' => '/vendor/zf1/library'
    ];

    (new Orno\Loader\Autoloader)
        ->registerPrefixes($prefixes)
        ->register();

    $httpRequest = new Zend_Http_Request; // e.g ZF1 prefixed components