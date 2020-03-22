<?php

/**
 * Автозагрузка классов из директории
 */
include  'vendor/autoload.php';

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

//Загружить приложение
spl_autoload_register(function (string $className){
    if (substr($className, 0, 3) == "App")
    require_once __DIR__.'\\..\\src\\'.$className.'.php';
});