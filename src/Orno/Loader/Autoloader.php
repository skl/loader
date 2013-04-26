<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
namespace Orno\Loader;

/**
 * Autoloader
 *
 * An autoloader object allowing a project to adhere to PSR-0
 */
class Autoloader
{
    /**
     * Array of registered namespace => path pairs
     *
     * @var array
     */
    protected $namespaces = [];

    /**
     * Array of registered prefix => path pairs
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Array of registered class locations
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['namespaces'])) {
            $this->registerNamespaces($config['namespaces']);
        }

        if (isset($config['prefixes'])) {
            $this->registerPrefixes($config['prefixes']);
        }

        if (isset($config['classes'])) {
            $this->registerClasses[$config['classes']];
        }
    }

    /**
     * Register Namespaces
     *
     * Register a namespace => path pair with the autoloader
     *
     * @param  array $namespaces
     * @return \Orno\Loader\Autoloader
     */
    public function registerNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $path) {
            $this->namespaces[$namespace] = $path;
        }
        return $this;
    }

    /**
     * Register Prefixes
     *
     * Register a prefix => path pair with the autoloader
     *
     * @param  array $prefixes
     * @return \Orno\Loader\Autoloader
     */
    public function registerPrefixes(array $prefixes)
    {
        foreach ($prefixes as $prefix => $path) {
            $this->prefixes[$prefix] = $path;
        }
        return $this;
    }

    /**
     * Register Classes
     *
     * Register a direct path to a class with the autoloader
     *
     * @param  array $classes
     * @return \Orno\Loader\Autoloader
     */
    public function registerClasses(array $classes)
    {
        foreach ($classes as $class => $path) {
            $this->classes[$class] = $path;
        }
        return $this;
    }

    /**
     * Get Namespaces
     *
     * Returns the namespace registry array
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Get Prefixes
     *
     * Returns the prefix registry array
     *
     * @return array
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * Get Classes
     *
     * Returns the class registry array
     *
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Resolve File
     *
     * Resolves a class file from a provided class
     *
     * @param  string $class
     * @return string
     */
    public function resolveFile($class)
    {
        // do we have anexact match?
        if (isset($this->getClasses()[$class])) {
            return $this->classes[$class];
        }

        // loop through the namespace registry
        foreach ($this->getNamespaces() as $namespace => $path) {
            $length = strlen($namespace);

            if (substr($class, 0, $length) !== $namespace) {
                continue;
            }

            return rtrim($path, '/') . DIRECTORY_SEPARATOR . $this->classToFile($class);
        }

        // loop through the prefix registry
        foreach ($this->getPrefixes() as $prefix => $path) {
            $length = strlen($prefix);

            if (substr($class, 0, $length) !== $prefix) {
                continue;
            }

            return rtrim($path, '/') . DIRECTORY_SEPARATOR . $this->classToFile($class);
        }
    }

    /**
     * Class to File
     *
     * Converts a class name to a filename
     *
     * @param  string $class
     * @return string
     */
    public function classToFile($class)
    {
        return str_replace(['_', '\\'], DIRECTORY_SEPARATOR, $class) . '.php';
    }

    /**
     * Is Loaded?
     *
     * Checks to see if a class is already loaded
     *
     * @param  string $class
     * @return boolean
     */
    public function isLoaded($class)
    {
        return class_exists($class, false) || interface_exists($class, false) || trait_exists($class, false);
    }

    /**
     * Autoload
     *
     * Autoload method to be registered with the spl autoload stack
     *
     * @param  string $class
     * @return void
     */
    public function autoload($class)
    {
        // is the class already loaded?
        if ($this->isLoaded($class)) {
            return;
        }

        $file = $this->resolveFile($class);

        if ($file !== null) {
            include $file;
            return;
        }
    }

    /**
     * Register
     *
     * Registers this autoloader with the spl autoload stack
     *
     * @param  boolean $prepend
     * @return Orno\Loader\Autoloader
     */
    public function register($prepend = true)
    {
        spl_autoload_register([$this, 'autoload'], true, (bool) $prepend);
        return $this;
    }

    /**
     * Unregister
     *
     * Unregisters this autoloader from the spl autoload stack
     *
     * @return Orno\Loader\Autoloader
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'autoload']);
        return $this;
    }
}
