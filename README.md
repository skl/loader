# Orno\Loader - PHP Autoloading Adhering to PSR-0

[![Buildi Status](https://travis-ci.org/orno/loader.png?branch=master)](https://travis-ci.org/orno/loader)

A simple autoloading library adhering to PSR-0.

### Usage

The best way to use the autoloader for performance reasons is to provide it with a class map.

    $classMap = [
        'Orno\Router\Routing'  => '/path/to/Orno/Router/Routing.php',
        'Orno\Router\RouteMap' => '/path/to/Orno/Router/RouteMap.php'
    ];

    (new Orno\Loader\Autoloader)
        ->registerClasses($classMap)
        ->register();

    $routing  = new Orno\Router\Routing;
    $routeMap = new Orno\Router\RouteMap;

Another option would be to set paths to namespaces and/or prefixes.

    $namespaces = [
        'Orno' => '/path/to/Orno',
        'Zend' => '/path/to/ZendFramework'
    ];

    (new Orno\Loader\Autoloader)
        ->registerNamespaces($namespaces)
        ->register();

    $routing     = new Orno\Router\Routing;
    $httpRequest = new Zend\Http\Request; // e.g. ZF2 namespaced components

or

    $prefixes = [
        'Zend_' => '/path/to/ZendFramework'
    ];

    (new Orno\Loader\Autoloader)
        ->registerPrefixes($prefixes)
        ->register();

    $httpRequest = new Zend_Http_Request; // e.g ZF1 prefixed components