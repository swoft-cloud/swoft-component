<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/26
 * Time: 17:06
 */

namespace Swoft\Encrypt\Mapping;

/**
 * Interface SecretKeyInterface
 * @package Swoft\Encrypt\Mapping
 */
interface SecretKeyInterface
{
    public function getPublicKey();
    public function getPrivateKey();
}