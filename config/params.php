<?php

return [
    'adminEmail' => 'admin@example.com',
    'mega'  =>  [
        'host' => '127.0.0.1',
        'port' => '9501',
        'mysql'    =>  [
            'dsn' => 'mysql:host=127.0.0.1;dbname=mega',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix'   => 'mega_',
        ],
    ]
];
