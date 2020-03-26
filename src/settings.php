<?php
return [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'gcfactory',
        'user' => 'default',
        'password' => 'root',
    ],
    'authorization' => [
        'expires_in' => 259200, // 3 days in seconds
        'refresh_in' => 86400, // 1 days in seconds
        'strict_to_ip' => true,
    ],
    'roles' => [
        240 => 'default',

        124 => 'moderator',

        0 => 'root'
    ]

];