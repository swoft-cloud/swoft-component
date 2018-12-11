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
    'consul' => [
        'address' => '',
        'port' => 8500,
        'register' => [
            'id' => '',
            'name' => '',
            'tags' => [],
            'enableTagOverride' => false,
            'service' => [
                'address' => 'localhost',
                'port' => '8099',
            ],
            'check' => [
                'id' => '',
                'name' => '',
                'tcp' => 'localhost:8099',
                'interval' => 10,
                'timeout' => 1,
            ],
        ],
        'discovery' => [
            'name' => 'user',
            'dc' => 'dc1',
            'near' => '',
            'tag' => '',
            'passing' => true
        ]
    ],
];
