<?php

return [
    '~^articles/(\d+)$~' => [\App\Controllers\ArticlesController::class, 'view'],
    '~^$~' => [\App\Controllers\MainController::class, 'main'],
];