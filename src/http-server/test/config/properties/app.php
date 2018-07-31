<?php
return [
    "version"           => '1.0',
    'autoInitBean'      => true,
    'beanScan'          => [
        'SwoftTest\\Testing'  => BASE_PATH . "/Testing",
        'Swoft\\Http\\Server' => BASE_PATH . '/../src',
    ],
    'I18n'              => [
        'sourceLanguage' => '@root/resources/messages/',
    ],
    'env'               => 'Base',
    'user.stelin.steln' => 'fafafa',
    'Service'           => [
        'user' => [
            'timeout' => 3000
        ]
    ],
    'cache' => require dirname(__FILE__) . DS . "cache.php",
];
