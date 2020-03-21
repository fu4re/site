<?php
try {
/**
* Автозагрузка классов из директории
*/
spl_autoload_register(function (string $className){
    require_once __DIR__.'\\src\\'.$className.'.php';
});

$route = $_GET['route'] ?? '';
$routes = require_once __DIR__.'\\src\\routes.php';

//Проверка соответсвию назначенных маршрутов
$isRouteFound = false;
foreach ($routes as $pattern => $controllerAndAction) {
    preg_match($pattern, $route, $matches);
    if (!empty($matches)) {
        $isRouteFound = true;
        break;
    }
}

var_dump($controllerAndAction);
var_dump($_SERVER);
// Если не найден маршрут
if (!$isRouteFound) {
   throw new \App\Exceptions\NotFoundException();
}

if (strtolower($_SERVER ['REQUEST_METHOD']) === $controllerAndAction[0])
{//TODO
    echo 'Wrong method';
    return;
}
unset($matches[0]);

$controllerName = $controllerAndAction[1];
$actionName = $controllerAndAction[2];

$controller = new $controllerName();
$controller->$actionName(...$matches);
} catch (\App\Exceptions\DBException $e) {
    $view = new \App\View\View(__DIR__ . '\\templates\\errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\App\Exceptions\NotFoundException $e) {
    $view = new \App\View\View(__DIR__ . '\\templates\\errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
}