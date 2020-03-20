<?php

/**
 * Автозагрузка классов из директории
 */
spl_autoload_register(function (string $className){
    require_once __DIR__.'\\src\\'.$className.'.php';
});

$controller = new \App\Controllers\MainController();
$controller->main();#