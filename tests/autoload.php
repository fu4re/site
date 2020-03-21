<?php

/**
 * Автозагрузка классов из директории
 */
include  'vendor/autoload.php';

//Загружить приложение
spl_autoload_register(function (string $className){
    if (substr($className, 0, 3) == "App")
    require_once __DIR__.'\\..\\src\\'.$className.'.php';
});