<?php

namespace App\Services;

use App\Exceptions\RouterException;
use \App\Exceptions\NotFoundException;

final class Router
{
    /** @var array Массив функций middleware */
    private static $middleware = [];
    /** @var array Массив маршрутов */
    private static $routes = [];

    /**
     * Функция загрузки маршрутов в роутер
     * @param array $routes
     *
     * @throws RouterException
     */
    public final static function applyRoutes(array $routes)
    {
        foreach ($routes as $route => $controller) {
            if ((is_string($route) && preg_match('~\~\^(.*)\$\~~', $route))) {
                if (class_exists($controller[0]) !== true) {
                    throw new RouterException('Invalid controller : ' . $controller[0]);
                }
            } else {
                throw new RouterException('Invalid route : ' . $route);
            }
        }
        self::$routes = $routes;
    }

    /**
     * Запуск роутера
     * @param string $reqURL Запрашиваемый URL
     *
     * @throws NotFoundException В случае если обработчик маршрута не найден
     */
    public final static function run(string $reqURL)
    {
        $isRouteFound = false;
        foreach (static::$routes as $routePattern => [$controllerName, $actionName]) {
            preg_match($routePattern, $reqURL, $matches);
            if (!empty($matches)) {
                $isRouteFound = true;
                break;
            }
        }
        if (!$isRouteFound) {
            throw new NotFoundException();
        }

        unset($matches[0]);

        static::beforeController();

        $controller = new $controllerName();
        $controller->$actionName(...$matches);
    }

    /**
     * Добавить функцию которая выполинтся до обработки запроса
     * @param callable $function Функция
     * @param mixed ...$args Параметры функции
     */
    public final static function addMiddleware(callable $function, ...$args)
    {
        array_push(static::$middleware, [$function, $args]);
    }

    private final static function beforeController()
    {
        if (count(static::$middleware) > 0)
        {
            foreach (static::$middleware as [$function, $arg])
            {
                call_user_func_array($function, $arg);
            }
        }
    }
}