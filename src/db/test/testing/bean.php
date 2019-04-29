<?php

use Swoft\Db\Database;
use Swoft\Db\Pool;

return [
    'config' => [
        'path' => __DIR__ . '/../config',
    ],
    'db'     => [
        'dns'      => 'mysql:dbname=swoft;host=127.0.0.1',
        'username' => 'root',
        'password' => '123456',
    ],
    'db2'      => [
        'class'  => Database::class,
        'writes' => [
            [
                'dsn'      => 'mysql:dbname=swoft;host=127.0.0.1',
                'username' => 'root',
                'password' => '123456',
            ]
        ],
        'reads'  => [
            [
                'dsn'      => 'mysql:dbname=swoft;host=127.0.0.1',
                'username' => 'root',
                'password' => '123456',
            ]
        ],
    ],
    'db.pool2' => [
        'class'    => Pool::class,
        'database' => \bean('db2')
    ]
];

