<?php
use PHPUnit\Framework\TestCase;

use App\Controllers;

/**
 * Class RoutesTest
 * @backupStaticAttributes enabled
 */
class RoutesTest extends TestCase
{
    protected static $pathToRoutes;

    public function setUp(): void
    {
        self::$pathToRoutes =  __DIR__.'/../src/routes.php';
    }

    /**
     * @coversNothing
     */
    public function testRoutesFileExists(): void
    {
        $this->assertFileExists(self::$pathToRoutes);
    }

    /**
     * @coversNothing
     * @depends testRoutesFileExists
     */
    public function testRoutesFileReturnsArray (): void
    {
        $routes = $this->getRoutes();
        $this->assertIsArray($routes, 'Не массив');
    }

    /**
     * @coversNothing
     * @depends testRoutesFileReturnsArray
     */
    public function testRegexIsValid (): void
    {
        $option = '~.*~';
        $keys = array_keys($this->getRoutes());
        foreach ($keys as $regularEx)
        {
            $this->assertRegExp($option, $regularEx, 'Неправильно заданное регулярное выражение');
        }
    }

    /**
     * @coversNothing
     * @depends testRoutesFileExists
     * @dataProvider getRoutes
     */
    public function testRouteHaveAllArguments (array $expectedRoutes): void
    {
        foreach ($expectedRoutes as $route => $options)
        {
            $this->assertCount(2, $options, 'Недостаточно аргументов для маршрута');
        }
    }

    /**
     * @coversNothing
     * @depends testRouteHaveAllArguments
     * @dataProvider getRoutes
     */
    public function testControllerClassExists (array $expectedRoutes): void
    {
        foreach ($expectedRoutes as $options)
        {
            $this->assertTrue(class_exists($options[0]), 'Класса '.$options[0].' не существует');
        }
    }

    /**
     * @coversNothing
     * @depends testControllerClassExists
     * @dataProvider getRoutes
     */
    public function testControllerClassHasMethod (array $expectedRoutes): void
    {
        $options = array_values($expectedRoutes)[0];
        $this->assertTrue(method_exists($options[0], $options[1]), 'Класс '.$options[0].' не обладает методом '.$options[1]);
    }

    /**
     * @coversNothing
     */
    public function getRoutes()
    {
        $array = (array)(require __DIR__.'/../src/routes.php');
        $result = [];
        foreach ($array as  $key => $value)
        {
            $result[$key] = [[$key => $value]];
        }
        return $result;
    }
}