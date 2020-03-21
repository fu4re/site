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

// Если не найден маршрут
if (!$isRouteFound) {
    echo 'Страница не найдена!';
    return;
}

unset($matches[0]);

$controllerName = $controllerAndAction[0];
$actionName = $controllerAndAction[1];

$controller = new $controllerName();
$controller->$actionName(...$matches);
} catch (\App\Exceptions\DBException $e) {
    $view = new \App\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
}