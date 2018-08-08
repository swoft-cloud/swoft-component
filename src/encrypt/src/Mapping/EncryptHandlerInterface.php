<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/26
 * Time: 16:56
 */

namespace Swoft\Encrypt\Mapping;

/**
 * Interface EncryptHandlerInterface
 * @package Swoft\Encrypt\Mapping
 */
interface EncryptHandlerInterface
{
    public function encrypt($data): string;
    public function decrypt(string $encryptData);
    public function sign($data);
    public function verify(string $data);
}