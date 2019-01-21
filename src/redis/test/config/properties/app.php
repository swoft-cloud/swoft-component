<?php
return [
    "version" => '1.0',
    'autoInitBean' => true,
    'beanScan' => [
        'SwoftTest\\Redis\\Pool' => BASE_PATH . "/Cases/Pool",
        'SwoftTest\\Redis\\Testing' => BASE_PATH . "/Testing",
        'Swoft\\Redis' => BASE_PATH . "/../src",
    ],
    'cache' => require __DIR__ . DS . "cache.php",
];
