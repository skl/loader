<?php

require realpath(__DIR__ . '/../library/Spry/Loader/Autoloader.php');

use Spry\Loader\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public $testData = [];

    public function setUp()
    {
        $this->testData = [
            'namespaces' => [
                'Spry\Test' => __DIR__ . '/Assets'
            ],
            'prefixes' => [
                'Spry_Test_' => __DIR__ . '/Assets'
            ],
            'classes' => [
                'Spry\Test\Foo' => __DIR__ . '/Assets/Foo.php',
                'Spry\Test\Bar' => __DIR__ . '/Assets/Bar.php'
            ]
        ];
    }

    public function tearDown()
    {
        $this->testData = [];
    }

    public function testAutoloaderResolvesNamespacedClasses()
    {
        $autoloader = new Autoloader;
        $autoloader->registerNamespaces($this->testData['namespaces']);

        $this->assertEquals(
            __DIR__ . '/Assets/Foo.php',
            $autoloader->resolveFile('Spry\Test\Foo')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Bar.php',
            $autoloader->resolveFile('Spry\Test\Bar')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Baz/Bar.php',
            $autoloader->resolveFile('Spry\Test\Baz\Bar')
        );
    }

    public function testAutoloaderResolvesPrefixedClasses()
    {
        $autoloader = new Autoloader;
        $autoloader->registerPrefixes($this->testData['prefixes']);

        $this->assertEquals(
            __DIR__ . '/Assets/FooPrefixed.php',
            $autoloader->resolveFile('Spry_Test_FooPrefixed')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/BarPrefixed.php',
            $autoloader->resolveFile('Spry_Test_BarPrefixed')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Baz/BarPrefixed.php',
            $autoloader->resolveFile('Spry_Test_Baz_BarPrefixed')
        );
    }

    public function testAutoloaderResolvesRegisteredClass()
    {
        $autoloader = new Autoloader;
        $autoloader->registerClasses($this->testData['classes']);

        $this->assertEquals(
            __DIR__ . '/Assets/Foo.php',
            $autoloader->resolveFile('Spry\Test\Foo')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Bar.php',
            $autoloader->resolveFile('Spry\Test\Bar')
        );
    }
}
