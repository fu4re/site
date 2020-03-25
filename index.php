<?php

try {
    /**
     * Автозагрузка классов из директории
     */
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '\\src\\' . $className . '.php';
    });

    \App\Services\Router::applyRoutes(require __DIR__.'\\src\\routes.php');
    \App\Services\Router::addMiddleware(function ($config)
    {
        \App\Services\Database::setOptions($config);
    }, require_once __DIR__.'\\src\\settings.php');

    $route = $_GET['route'] ?? '';

    \App\Services\Router::run($route);

    var_dump($_GET);
} catch (\App\Exceptions\RouterException $e)
{

} catch (\App\Exceptions\NotFoundException $e) {
} catch (Exception $e) {
}