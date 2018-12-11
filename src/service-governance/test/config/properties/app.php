<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
