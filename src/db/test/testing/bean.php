<?php

use Swoft\Db\Database;
use Swoft\Db\Pool;

return [
    'config'   => [
        'path' => __DIR__ . '/../config',
    ],
    'db'     => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test;host=172.17.0.2',
        'username' => 'root',
        'password' => '123456'
    ],
    'db2'      => [
        'class'  => Database::class,
        'writes' => [
            [
                'dsn'      => 'mysql:dbname=test;host=172.17.0.2',
                'username' => 'root',
                'password' => '123456',
            ],
        ],
        'reads'  => [
            [
                'dsn'      => 'mysql:dbname=test;host=172.17.0.2',
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

