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
    'version' => '1.0',
    'autoInitBean' => true,
    'bootScan' => [
        'Swoft\\Auth' => BASE_PATH . '/../src',
    ],
    'beanScan' => [
        'Swoft\\Encrypt' => BASE_PATH . '/../src',
        'SwoftTest\\Auth\\Account'=> BASE_PATH .'/Cases/Account'
    ],
    'I18n' => [
        'sourceLanguage' => '@root/resources/messages/',
    ],
    'env' => 'Base',
    'encrypt'      => [
        'publicKey' => '@resources/key/rsa_public_key.pem',
        'privateKey' => '@resources/key/rsa_private_key.pem',
        'padding' => OPENSSL_PKCS1_PADDING,
    ],
    'Service' => [
        'user' => [
            'timeout' => 3000
        ]
    ],
    'cache' => require dirname(__FILE__) . DS . 'cache.php',
];
