<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/26
 * Time: 17:05
 */

namespace Swoft\Encrypt;

use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Encrypt\Mapping\SecretKeyInterface;

/**
 * @Bean()
 * Class SecretKey
 * @package Swoft\Encrypt
 */
class SecretKey implements SecretKeyInterface
{
    /**
     * @Value(name="${config.encrypt.publicKey}", env="${ENCRYPT_PUBLIC_KEY}")
     * @var string
     */
    private $publicKey = '';

    /**
     * @Value(name="${config.encrypt.privateKey}", env="${ENCRYPT_PRIVATE_KEY}")
     * @var string
     */
    private $privateKey = '';

    public function getPublicKey()
    {
        return openssl_pkey_get_public(file_get_contents(App::getAlias($this->publicKey)));
    }

    public function getPrivateKey()
    {
        return openssl_pkey_get_private(file_get_contents(App::getAlias($this->privateKey)));
    }
}