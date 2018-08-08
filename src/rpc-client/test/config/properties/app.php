<?php
return [
    "version"           => '1.0',
    'autoInitBean'      => true,
    'beanScan'          => [
        'SwoftTest\\Rpc\\Testing' => BASE_PATH . "/Testing",
        'Swoft\\Rpc\\Client'      => BASE_PATH . '/../src',
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
    'components' => [
        'custom' => [
            'Swoft\\Rpc\\Client' => BASE_PATH . '/../src',
        ],
    ],
    'cache' => require dirname(__FILE__) . DS . "cache.php",
];
