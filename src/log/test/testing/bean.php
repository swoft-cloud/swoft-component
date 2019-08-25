<?php

use Monolog\Formatter\LineFormatter;
use Swoft\Log\Handler\FileHandler;
use Swoft\Log\Logger;

return [
    'lineFormatter'      => [
        'class'      => LineFormatter::class,
        'format'     => '%datetime% [%level_name%] [%channel%] [%event%] [tid:%tid%] [cid:%cid%] [traceid:%traceid%] [spanid:%spanid%] [parentid:%parentid%] %messages%',
        'dateFormat' => 'Y-m-d H:i:s',
    ],
    'testFileHandler' => [
        'class'     => FileHandler::class,
        'logFile'   => '@runtime/logs/error.log',
        'formatter' => bean('lineFormatter'),
        'levels'    => 'error,warning',
    ],
    'logger'             => [
        'class'        => Logger::class,
        'flushRequest' => false,
        'enable'       => false,
        'handlers'     => [
            'application' => bean('applicationHandler'),
            'notice'      => bean('noticeHandler'),
        ],
    ]
];