<?php

return [
    '~^articles/(\d+)$~' => [\App\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\App\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\App\Controllers\ArticlesController::class, 'add'],
    '~^$~' => [\App\Controllers\MainController::class, 'main'],
];