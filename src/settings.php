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
        'strict_to_ip' => true,
    ]
];