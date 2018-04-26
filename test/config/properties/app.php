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
    'version'      => '1.0',
    'autoInitBean' => true,
    'beanScan'     => [
        'SwoftTest\\Db\\Testing'             => SRC_PATH . '/db/test/Testing',
        'Swoft\\Db'                          => SRC_PATH . '/db/src',
        'SwoftTest\\Aop'                     => SRC_PATH . '/framework/test/Cases/Aop',
        'SwoftTest\\Bean'                    => SRC_PATH . '/framework/test/Cases/Bean',
        'SwoftTest\\Pool'                    => SRC_PATH . '/framework/test/Cases/Pool',
        'Swoft\\Http\\Server\\Test\\Testing' => SRC_PATH . '/http-server/test/Testing',
        'Swoft\\I18n'                        => SRC_PATH . '/i18n/src',
        'SwoftTest\\Redis\\Pool'             => SRC_PATH . '/redis/test/Cases/Pool',
        'Swoft\\Redis'                       => SRC_PATH . '/redis/src',
        'Swoft\\Rpc\\Client\\Testing'        => SRC_PATH . '/rpc-client/test/Testing',
        'SwoftTest\\Task\\Tasks'             => SRC_PATH . '/task/test/Cases/Tasks',
        'Swoft\\Tasks'                       => SRC_PATH . '/task/src',
        'Swoft\\View\\Test\\Testing'         => SRC_PATH . '/view/test/Testing',
    ],
    'devtool'      => [
        'enable'                  => false,
        'logEventToConsole'       => false,
        'logHttpRequestToConsole' => false,
    ],
    'env'          => 'Base',
    'db'           => require __DIR__ . DS . 'db.php',
    'cache'        => require __DIR__ . DS . 'cache.php',
    'provider'     => require __DIR__ . DS . 'provider.php',
    'test'         => require __DIR__ . DS . 'test.php',
];
