<?php declare(strict_types=1);


namespace SwoftTest\Redis;

use Swoft\Redis\Redis;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public function setKey(string $value): string
    {
        $key = uniqid();
        Redis::set($key, $value);

        return $key;
    }
}