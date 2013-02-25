<?php

namespace Orno\LoaderTest;

require realpath(__DIR__ . '/../src/Orno/Loader/Autoloader.php');

use Orno\Loader\Autoloader;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [
                // namespaces
                ['Orno\Tests' => __DIR__ . '/assets'],
                // prefixes
                ['Orno_Tests_' => __DIR__ . '/assets'],
                // class map
                ['Orno\Tests\Foo' => __DIR__ . '/assets/Orno/Tests/Foo.php', 'Orno\Tests\Bar' => __DIR__ . '/assets/Orno/Tests/Bar.php']
            ]
        ];
    }

    /**
     * Test to assert that a file can be resolved from the Namespace registry.
     * @dataProvider provider
     */
    public function testAutoloaderResolvesNamespacedClasses($namespaces, $prefixes, $classes)
    {
        $autoloader = (new Autoloader)->registerNamespaces($namespaces);

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/Foo.php',
            $autoloader->resolveFile('Orno\Tests\Foo')
        );

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/Bar.php',
            $autoloader->resolveFile('Orno\Tests\Bar')
        );

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/Baz/Bar.php',
            $autoloader->resolveFile('Orno\Tests\Baz\Bar')
        );
    }

    /**
     * Test to assert that a file can be resolved from the Prefix registry.
     * @dataProvider provider
     */
    public function testAutoloaderResolvesPrefixedClasses($namespaces, $prefixes, $classes)
    {
        $autoloader = (new Autoloader)->registerPrefixes($prefixes);

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/FooPrefixed.php',
            $autoloader->resolveFile('Orno_Tests_FooPrefixed')
        );

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/BarPrefixed.php',
            $autoloader->resolveFile('Orno_Tests_BarPrefixed')
        );

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/Baz/BarPrefixed.php',
            $autoloader->resolveFile('Orno_Tests_Baz_BarPrefixed')
        );
    }

    /**
     * Test to assert that a file can be resolved from the Class map registry.
     * @dataProvider provider
     */
    public function testAutoloaderResolvesRegisteredClass($namespaces, $prefixes, $classes)
    {
        $autoloader = (new Autoloader)->registerClasses($classes);

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/Foo.php',
            $autoloader->resolveFile('Orno\Tests\Foo')
        );

        $this->assertSame(
            __DIR__ . '/assets/Orno/Tests/Bar.php',
            $autoloader->resolveFile('Orno\Tests\Bar')
        );
    }

    /**
     * Test to assert that the file is loaded.
     * @dataProvider provider
     */
    public function testAutoloderIncludesClassFile($namespaces, $prefixes, $classes)
    {
        (new Autoloader)
            ->registerClasses($classes)
            ->register();

        $this->assertTrue(class_exists('Orno\Tests\Foo'));
        $this->assertTrue(class_exists('Orno\Tests\Bar'));
    }
}
