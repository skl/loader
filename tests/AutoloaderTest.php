<?php

require realpath(__DIR__ . '/../library/Orno/Loader/Autoloader.php');

use Orno\Loader\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public $testData = [];

    public function setUp()
    {
        $this->testData = [
            'namespaces' => [
                'Orno\Tests' => __DIR__ . '/Assets'
            ],
            'prefixes' => [
                'Orno_Tests_' => __DIR__ . '/Assets'
            ],
            'classes' => [
                'Orno\Tests\Foo' => __DIR__ . '/Assets/Foo.php',
                'Orno\Tests\Bar' => __DIR__ . '/Assets/Bar.php'
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
            $autoloader->resolveFile('Orno\Tests\Foo')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Bar.php',
            $autoloader->resolveFile('Orno\Tests\Bar')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Baz/Bar.php',
            $autoloader->resolveFile('Orno\Tests\Baz\Bar')
        );
    }

    public function testAutoloaderResolvesPrefixedClasses()
    {
        $autoloader = new Autoloader;
        $autoloader->registerPrefixes($this->testData['prefixes']);

        $this->assertEquals(
            __DIR__ . '/Assets/FooPrefixed.php',
            $autoloader->resolveFile('Orno_Tests_FooPrefixed')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/BarPrefixed.php',
            $autoloader->resolveFile('Orno_Tests_BarPrefixed')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Baz/BarPrefixed.php',
            $autoloader->resolveFile('Orno_Tests_Baz_BarPrefixed')
        );
    }

    public function testAutoloaderResolvesRegisteredClass()
    {
        $autoloader = new Autoloader;
        $autoloader->registerClasses($this->testData['classes']);

        $this->assertEquals(
            __DIR__ . '/Assets/Foo.php',
            $autoloader->resolveFile('Orno\Tests\Foo')
        );

        $this->assertEquals(
            __DIR__ . '/Assets/Bar.php',
            $autoloader->resolveFile('Orno\Tests\Bar')
        );
    }

    public function testAutoloderIncludesClassFile()
    {
        $autoloader = new Autoloader;
        $autoloader->registerClasses($this->testData['classes'])
                   ->register();

        $foo = new Orno\Tests\Foo;
        $bar = new Orno\Tests\Bar;

        $this->assertTrue($autoloader->isLoaded('Orno\Tests\Foo'));
        $this->assertTrue($autoloader->isLoaded('Orno\Tests\Bar'));
    }
}
