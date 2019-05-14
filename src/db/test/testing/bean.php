<?php

use Swoft\Db\Database;
use Swoft\Db\Pool;

return [
    'config'   => [
        'path' => __DIR__ . '/../config',
    ],
    'db'     => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test;host=127.0.0.1',
        'username' => 'root',
        'password' => 'swoft123456',
        'charset'  => 'utf8mb4',
        // 'prefix'   => 't_',
        'options'  => [
            \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
        ],
        'config'   => [
            'collation' => 'utf8mb4_unicode_ci',
            'strict'    => false,
            'timezone'  => '+8:00',
            'modes'     => 'NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES',
        ],
    ],
    'db2'      => [
        'class'  => Database::class,
        'writes' => [
            [
                'dsn'      => 'mysql:dbname=test;host=127.0.0.1',
                'username' => 'root',
                'password' => 'swoft123456',
            ],
        ],
        'reads'  => [
            [
                'dsn'      => 'mysql:dbname=test;host=127.0.0.1',
                'username' => 'root',
                'password' => 'swoft123456',
            ]
        ],
    ],
    'db.pool2' => [
        'class'    => Pool::class,
        'database' => \bean('db2')
    ]
];

