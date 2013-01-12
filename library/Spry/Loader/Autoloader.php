<?php namespace Spry\Loader;

class Autoloader
{
    /**
     * Array of registered namespace => path pairs
     * @var array
     */
    protected $namespaces = [];

    /**
     * Array of registered prefix => path pairs
     * @var array
     */
    protected $prefixes = [];

    /**
     * Array of registered class locations
     * @var array
     */
    protected $classes = [];

    /**
     * Register a namespace => path pair with the autoloader
     * @param  array       $namespaces
     * @return Autoloader  $this
     */
    public function registerNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $path) {
            $this->namespaces[$namespace] = $path;
        }
        return $this;
    }

    /**
     * Register a prefix => path pair with the autoloader
     * @param  array  $prefixes
     * @return Autoloader  $this
     */
    public function registerPrefixes(array $prefixes)
    {
        foreach ($prefixes as $prefix => $path) {
            $this->prefixes[$prefix] = $path;
        }
        return $this;
    }

    /**
     * Register a direct path to a class with the autoloader
     * @param  array  $classes
     * @return Autoloader  $this
     */
    public function registerClasses(array $classes)
    {
        foreach ($classes as $class => $path) {
            $this->classes[$class] = $path;
        }
        return $this;
    }

    /**
     * Returns the namespace registry array
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Returns the prefix registry array
     * @return array
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * Returns the class registry array
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Resolves a class file from a provided class
     * @param  string  $class
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

            $class = str_replace($namespace, null, $class);

            return rtrim($path, '/') . $this->classToFile($class);
        }

        // loop through the prefix registry
        foreach ($this->getPrefixes() as $prefix => $path) {
            $length = strlen($prefix);

            if (substr($class, 0, $length) !== $prefix) {
                continue;
            }

            $class = str_replace($prefix, null, $class);

            return rtrim($path, '/') . DIRECTORY_SEPARATOR . $this->classToFile($class);
        }
    }

    /**
     * Converts a class name to a filename
     * @param  string  $class
     * @return string
     */
    public function classToFile($class)
    {
        return str_replace(['_', '\\'], DIRECTORY_SEPARATOR, $class) . '.php';
    }
}