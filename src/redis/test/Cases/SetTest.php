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

/**
 * SetTest
 */
class SetTest extends AbstractTestCase
{
    public function testSaddAndSMembers()
    {
        $key    = uniqid();
        $value1 = uniqid();
        $value2 = uniqid();
        $value3 = uniqid();
        $value4 = uniqid();

        $this->redis->sAdd($key, $value1, $value2, $value3);
        $this->redis->sAdd($key, $value4);

        $values = [$value1, $value2, $value3, $value4];

        $members = $this->redis->sMembers($key);
        sort($members);
        sort($values);

        $this->assertSame($members, $values);

        $this->assertPrefix($key);
    }

    public function testSremoveAndScontainsAndScard()
    {
        $key    = uniqid();
        $value1 = uniqid();
        $value2 = uniqid();
        $this->redis->sAdd($key, $value1, $value2);
        $result = $this->redis->sMembers($key);
        $this->assertCount(2, $result);

        $result = $this->redis->sIsMember($key, $value1);
        $this->assertTrue($result);

        $result = $this->redis->sCard($key);
        $this->assertSame(2, $result);

        $result = $this->redis->sRem($key, $value1);
        $this->assertSame(1, $result);

        $members = $this->redis->sMembers($key);
        $this->assertCount(1, $members);

        $this->assertPrefix($key);
    }
}