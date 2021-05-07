<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Redis\Unit;

use Swoft\Redis\Redis;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class RedisTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function setKey(string $value): string
    {
        $key = $this->uniqId();
        Redis::set($key, $value);

        return $key;
    }

    /**
     * @return string
     */
    protected function uniqId(): string
    {
        return uniqid('', true);
    }
}
