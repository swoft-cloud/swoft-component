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
    'version' => '1.0',
    'autoInitBean' => true,
    'beanScan' => [
        'SwoftTest\\Redis\\Pool' => BASE_PATH . '/Cases/Pool',
        'SwoftTest\\Redis\\Testing' => BASE_PATH . '/Testing',
        'Swoft\\Redis' => BASE_PATH . '/../src',
    ],
    'cache' => require __DIR__ . DS . 'cache.php',
];
