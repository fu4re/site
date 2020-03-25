<?php

return [
    '~^$~' => [ \App\Controllers\MainController::class, 'main'],
    '~^users/registry$~' => [\App\Controllers\UsersController::class, 'signUp'],
    '~^users/login$~' => [\App\Controllers\UsersController::class, 'signIn'],
];