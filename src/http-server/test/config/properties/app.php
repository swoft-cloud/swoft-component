<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
return [
    'version'           => '1.0',
    'autoInitBean'      => true,
    'beanScan'          => [
        'SwoftTest\\HttpServer\\Testing'  => BASE_PATH . '/Testing',
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
    'cache' => require dirname(__FILE__) . DS . 'cache.php',
];