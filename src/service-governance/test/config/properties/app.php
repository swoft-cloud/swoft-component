<?php
return [
    'autoInitBean' => true,
    'beanScan' => [
    ],
    'provider' => require __DIR__ . DS . 'provider.php',
    'components' => [
        'custom' => [
            'Swoft\\Sg' => BASE_PATH . '/../src',
        ],
    ],
];
