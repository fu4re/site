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
    }, (require_once __DIR__.'\\src\\settings.php')['db']);

    $route = $_GET['route'] ?? '';

    \App\Services\Router::run($route);

} catch (\App\Exceptions\RouterException $e)
{

} catch (\App\Exceptions\DBException $e) {
    $view = new \App\View\View(__DIR__ . '\\templates\\errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\App\Exceptions\AuthorizationException $e)
{
    $view = new \App\View\View(__DIR__ . '\\templates');
    $view->renderHtml('users/login.php', ['error' => $e->getMessage()], 402);
} catch (\App\Exceptions\NotFoundException $e) {
    $view = new \App\View\View(__DIR__ . '\\templates\\errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
}