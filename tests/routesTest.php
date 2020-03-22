<?php
use PHPUnit\Framework\TestCase;

use App\Controllers;

class RoutesTest extends TestCase
{
    protected $pathToRoutes;

    public function setUp(): void
    {
        $this->pathToRoutes =  __DIR__.'/../src/routes.php';
    }

    /**
     * @coversNothing
     */
    public function testRoutesFileExists(): void
    {
        $this->assertFileExists($this->pathToRoutes);
    }

    /**
     * @coversNothing
     * @depends testRoutesFileExists
     */
    public function testRoutesReturnsArray (): void
    {
        $routes = $this->getRoutes();
        $this->assertIsArray($routes, 'Не массив');
    }

    /**
     * @coversNothing
     * @depends testRoutesFileExists
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
     */
    public function testRouteHaveAllArguments (): void
    {
        $routeOptions = array_values($this->getRoutes());
        foreach ($routeOptions as $options)
        {
            $this->assertCount(3, $options, 'Недостаточно аргументов для маршрута');
        }
    }

    /**
     * @coversNothing
     * @depends testRouteHaveAllArguments
     */
    public function testRequestMethodIsValid (): void
    {
        $routeOptions = $this->getRoutes();
        foreach ($routeOptions as $regex => $options)
        {
            $this->assertIsString($options[0], 'Неправильно задан метод запроса '.$regex);
        }
    }

    /**
     * @coversNothing
     * @depends testRouteHaveAllArguments
     */
    public function testControllerClassExists (): void
    {
        $routeOptions = array_values($this->getRoutes());
        foreach ($routeOptions as $options)
        {
            $this->assertTrue(class_exists($options[1]), 'Класса '.$options[1].' не существует');
        }
    }

    /**
     * @coversNothing
     * @depends testControllerClassExists
     */
    public function testControllerClassHasMethod (): void
    {
        $routeOptions = array_values($this->getRoutes());
        foreach ($routeOptions as $options)
        {
            $object = new $options[1];
            $this->assertTrue(method_exists($object, $options[2]), 'Класс '.$options[1].' не обладает методом '.$options[2]);
        }
    }

    /**
     * @coversNothing
     * @coversNothing
     */
    public function getRoutes()
    {
        return (require $this->pathToRoutes);
    }
}