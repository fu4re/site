<?php

return [
    '~^$~' => [ \App\Controllers\MainController::class, 'main'],
    '~^users/registry$~' => [\App\Controllers\UsersController::class, 'signUp'],
    '~^users/login$~' => [\App\Controllers\UsersController::class, 'signIn'],
    '~^users/(\d+)/activate/(.+)$~' => [\App\Controllers\UsersController::class, 'activate'],
    '~^users/logout$~' => [\App\Controllers\UsersController::class, 'exit'],
];