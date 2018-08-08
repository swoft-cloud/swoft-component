<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/23
 * Time: 10:45
 */

namespace Swoft\Encrypt\Bean\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("ALL")
 * Class Encrypt
 * @package Swoft\Encrypt\Bean\Annotation
 */
class Encrypt
{
    const BEFORE_VERIFY = 'verify';
    const BEFORE_DECRYPT = 'decrypt';
    const AFTER_SIGN = 'sign';
    const AFTER_ENCRYPT = 'encrypt';

    private $before;

    private $after;

    private $publicKey;

    private $privateKey;

    public function __construct(array $values)
    {
        if (isset($values['before'])) {
            $this->before = $values['before'];
        }

        if (isset($values['after'])) {
            $this->after = $values['after'];
        }

        if (isset($values['publicKey'])) {
            $this->publicKey = $values['publicKey'];
        }

        if (isset($values['privateKey'])) {
            $this->privateKey = $values['privateKey'];
        }
    }

    /**
     * @return mixed|string
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param mixed|string $before
     */
    public function setBefore($before)
    {
        $this->before = $before;
    }

    /**
     * @return mixed|string
     */
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * @param mixed|string $after
     */
    public function setAfter($after)
    {
        $this->after = $after;
    }

    /**
     * @return mixed|string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param mixed|string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return mixed|string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param mixed|string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }
}