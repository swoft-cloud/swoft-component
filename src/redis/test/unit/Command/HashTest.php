<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Redis\Unit\Command;

use Swoft\Redis\Redis;
use SwoftTest\Redis\Unit\RedisTestCase;
use function uniqid;
use function count;

/**
 * Class HashTest
 *
 * @since 2.0
 */
class HashTest extends RedisTestCase
{
    public function testhMsetAndhMget(): void
    {
        $key    = $this->uniqId();
        $values = [
            'key'  => [$this->uniqId()],
            'key2' => new self(),
        ];

        $result = Redis::hMSet($key, $values);
        $this->assertTrue($result);

        $getKeys   = array_keys($values);
        $getKeys[] = 'key3';

        $resultValues = Redis::hMGet($key, $getKeys);

        $this->assertEquals(count($resultValues), 2);
        $this->assertEquals($resultValues, $values);
    }
}
