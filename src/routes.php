<?php

return [
    '~^articles/(\d+)$~' => [ 'get', \App\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [ 'get', \App\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [ 'get', \App\Controllers\ArticlesController::class, 'add'],
    '~^$~' => [ 'get', \App\Controllers\MainController::class, 'main'],
];