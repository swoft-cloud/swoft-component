<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Redis\Cases;

use Swoft\App;

/**
 * StringTest
 */
class StringTest extends AbstractTestCase
{
    public function testSet()
    {
        $value = uniqid();
        $key = 'stringKey';
        if (App::isCoContext()) {
            $key .= 'co';
        }

        $nottlResult = $this->redis->set($key . 'key2', uniqid());
        $this->assertTrue($nottlResult);

        $result = $this->redis->set($key, $value, 100);
        $this->assertTrue($result);

        $ttl = $this->redis->ttl($key);
        $this->assertGreaterThan(1, $ttl);

        $getValue = $this->redis->get($key);
        $this->assertSame($getValue, $value);
    }

    public function testGet()
    {
        $default = 'defualtValue';
        $result = $this->redis->get('notKey' . uniqid(), $default);
        $this->assertSame($result, $default);
    }

    public function testMsetAndMget()
    {
        $key = uniqid();
        $key2 = uniqid();
        $value = 'value1';
        $value2 = 'val2';

        $result = $this->redis->mset([
            $key => $value,
            $key2 => $value2,
        ]);

        $this->assertTrue($result);

        $values = $this->redis->mget([$key2, $key]);
        $this->assertSame($values[$key], $value);
        $this->assertSame($values[$key2], $value2);
    }

    public function testHyperLoglog()
    {
        $this->redis->delete('pf:test');
        $this->redis->delete('pf:test2');
        $this->redis->delete('pf:test3');

        $result = $this->redis->pfAdd('pf:test', [1, 2, 3]);

        $this->assertSame(1, $result);

        $result = $this->redis->pfCount('pf:test');
        $this->assertSame(3, $result);

        $result = $this->redis->pfAdd('pf:test2', [3, 4, 5]);
        $this->assertSame(1, $result);

        $result = $this->redis->pfMerge('pf:test3', ['pf:test', 'pf:test2']);
        $this->assertTrue($result);

        $result = $this->redis->pfCount('pf:test3');
        $this->assertSame(5, $result);

        $result = $this->redis->pfCount(['pf:test', 'pf:test2']);
        $this->assertSame(5, $result);
    }
}
