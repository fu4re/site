<?php
use PHPUnit\Framework\TestCase;

use App\Controllers;

class routesTest extends TestCase
{
    protected $pathToRoutes;

    public function setUp(): void
    {
        $this->pathToRoutes =  __DIR__.'/../src/routes.php';
    }

    public function testIfRoutesFileExist(): void
    {
        $this->assertFileExists($this->pathToRoutes);
    }

    /**
     * @depends testIfRoutesFileExist
     */
    public function testIfRoutesIsArray (): void
    {
        $routes = $this->getRoutes();
        $this->assertIsArray($routes, 'Не массив');
    }

    /**
     * @depends testIfRoutesFileExist
     */
    public function testIfRegExIsValid (): void
    {
        $option = '~.*~';
        $keys = array_keys($this->getRoutes());
        foreach ($keys as $regularEx)
        {
            $this->assertRegExp($option, $regularEx, 'Неправильно заданное регулярное выражение');
        }
    }

    /**
     * @depends testIfRoutesFileExist
     */
    public function testIfRouteHaveAllArguments (): void
    {
        $routeOptions = array_values($this->getRoutes());
        foreach ($routeOptions as $options)
        {
            $this->assertCount(3, $options, 'Недостаточно аргументов для маршрута');
        }
    }

    /**
     * @depends testIfRouteHaveAllArguments
     */
    public function testIfRequestMethodIsValid (): void
    {
        $routeOptions = $this->getRoutes();
        foreach ($routeOptions as $regex => $options)
        {
            $this->assertIsString($options[0], 'Неправильно задан метод запроса '.$regex);
        }
    }

    /**
     * @depends testIfRouteHaveAllArguments
     */
    public function testIfControllerClassExist (): void
    {
        $routeOptions = array_values($this->getRoutes());
        foreach ($routeOptions as $options)
        {
            $this->assertTrue(class_exists($options[1]), 'Класса '.$options[1].' не существует');
        }
    }

    /**
     * @depends testIfControllerClassExist
     */
    public function testIfControllerClassHasMethod (): void
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
     */
    public function getRoutes()
    {
        return (require $this->pathToRoutes);
    }
}